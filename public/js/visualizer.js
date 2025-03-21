/**
 * Octaverum AI - Audio Visualizer
 * Creates visualizations from audio data using Web Audio API
 */

let visualizerCanvas;
let visualizerContext;
let analyser;
let dataArray;
let bufferLength;
let animationFrameId;
let visualizationType = 'waveform'; // Default visualization type
let particlesArray = [];

// Check for user preference from session data
if (typeof userVisualizationPreference !== 'undefined') {
    visualizationType = userVisualizationPreference;
}

/**
 * Initialize the visualizer
 * @param {AnalyserNode} analyserNode - Web Audio API analyser node
 */
function initVisualizer(analyserNode) {
    // Exit if no canvas is available
    visualizerCanvas = document.getElementById('visualization');
    if (!visualizerCanvas) return;
    
    visualizerContext = visualizerCanvas.getContext('2d');
    analyser = analyserNode;
    
    // Set canvas size to match container
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    // Set up the analyser
    analyser.fftSize = 256;
    bufferLength = analyser.frequencyBinCount;
    dataArray = new Uint8Array(bufferLength);
    
    // Initialize particles for particle visualization
    if (visualizationType === 'particles') {
        initParticles();
    }
    
    // Start the visualization loop
    visualize();
}

/**
 * Resize canvas to match container dimensions
 */
function resizeCanvas() {
    if (!visualizerCanvas) return;
    
    const container = visualizerCanvas.parentElement;
    visualizerCanvas.width = container.clientWidth;
    visualizerCanvas.height = container.clientHeight;
}

/**
 * Main visualization loop
 */
function visualize() {
    // Cancel previous animation frame if any
    if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
    }
    
    // Request new frame
    animationFrameId = requestAnimationFrame(visualize);
    
    // Get audio data
    analyser.getByteFrequencyData(dataArray);
    
    // Clear the canvas
    visualizerContext.clearRect(0, 0, visualizerCanvas.width, visualizerCanvas.height);
    
    // Choose visualization type
    switch (visualizationType) {
        case 'waveform':
            drawWaveform();
            break;
        case 'bars':
            drawBars();
            break;
        case 'circles':
            drawCircular();
            break;
        case 'particles':
            drawParticles();
            break;
        default:
            drawWaveform();
    }
}

/**
 * Draw a waveform visualization
 */
function drawWaveform() {
    analyser.getByteTimeDomainData(dataArray);
    
    // Set color and style
    const gradient = visualizerContext.createLinearGradient(0, 0, visualizerCanvas.width, 0);
    gradient.addColorStop(0, 'rgba(15, 247, 239, 0.8)');
    gradient.addColorStop(0.5, 'rgba(193, 101, 221, 0.8)');
    gradient.addColorStop(1, 'rgba(247, 42, 138, 0.8)');
    
    visualizerContext.strokeStyle = gradient;
    visualizerContext.lineWidth = 2;
    visualizerContext.shadowBlur = 10;
    visualizerContext.shadowColor = 'rgba(15, 247, 239, 0.5)';
    
    // Begin drawing
    visualizerContext.beginPath();
    
    const sliceWidth = visualizerCanvas.width * 1.0 / bufferLength;
    let x = 0;
    
    for (let i = 0; i < bufferLength; i++) {
        const v = dataArray[i] / 128.0;
        const y = v * visualizerCanvas.height / 2;
        
        if (i === 0) {
            visualizerContext.moveTo(x, y);
        } else {
            visualizerContext.lineTo(x, y);
        }
        
        x += sliceWidth;
    }
    
    visualizerContext.lineTo(visualizerCanvas.width, visualizerCanvas.height / 2);
    visualizerContext.stroke();
}

/**
 * Draw a frequency bars visualization
 */
function drawBars() {
    const barWidth = (visualizerCanvas.width / bufferLength) * 2.5;
    let barHeight;
    let x = 0;
    
    for (let i = 0; i < bufferLength; i++) {
        barHeight = dataArray[i] / 255 * visualizerCanvas.height;
        
        // Calculate color based on frequency intensity
        const h = i / bufferLength * 360;
        const s = 90;
        const l = 50;
        
        visualizerContext.fillStyle = `hsl(${h}, ${s}%, ${l}%)`;
        
        // Add glow effect
        visualizerContext.shadowBlur = 15;
        visualizerContext.shadowColor = `hsl(${h}, ${s}%, ${l}%)`;
        
        // Draw bar
        visualizerContext.fillRect(x, visualizerCanvas.height - barHeight, barWidth, barHeight);
        
        x += barWidth + 1;
    }
}

/**
 * Draw a circular visualization
 */
function drawCircular() {
    const centerX = visualizerCanvas.width / 2;
    const centerY = visualizerCanvas.height / 2;
    const radius = Math.min(centerX, centerY) * 0.7;
    
    visualizerContext.fillStyle = 'rgba(30, 30, 50, 0.2)';
    visualizerContext.beginPath();
    visualizerContext.arc(centerX, centerY, radius * 0.95, 0, Math.PI * 2);
    visualizerContext.fill();
    
    // Draw outer circle
    visualizerContext.beginPath();
    visualizerContext.arc(centerX, centerY, radius, 0, Math.PI * 2);
    visualizerContext.strokeStyle = 'rgba(15, 247, 239, 0.5)';
    visualizerContext.lineWidth = 2;
    visualizerContext.stroke();
    
    // Draw frequency data
    for (let i = 0; i < bufferLength; i++) {
        const amplitude = dataArray[i] / 256;
        const angle = i * (Math.PI * 2) / bufferLength;
        
        const x = centerX + Math.cos(angle) * radius;
        const y = centerY + Math.sin(angle) * radius;
        
        const lineLength = amplitude * (radius * 0.5);
        
        const xEnd = centerX + Math.cos(angle) * (radius + lineLength);
        const yEnd = centerY + Math.sin(angle) * (radius + lineLength);
        
        visualizerContext.beginPath();
        visualizerContext.moveTo(x, y);
        visualizerContext.lineTo(xEnd, yEnd);
        
        // Calculate color
        const h = i / bufferLength * 360;
        visualizerContext.strokeStyle = `hsl(${h}, 100%, 60%)`;
        visualizerContext.lineWidth = 2;
        visualizerContext.shadowBlur = 10;
        visualizerContext.shadowColor = `hsl(${h}, 100%, 60%)`;
        
        visualizerContext.stroke();
    }
    
    // Draw inner circle
    const averageAmplitude = dataArray.reduce((a, b) => a + b, 0) / bufferLength / 255;
    const pulseRadius = radius * 0.3 * (0.8 + averageAmplitude * 0.5);
    
    visualizerContext.beginPath();
    visualizerContext.arc(centerX, centerY, pulseRadius, 0, Math.PI * 2);
    
    const gradient = visualizerContext.createRadialGradient(
        centerX, centerY, 0,
        centerX, centerY, pulseRadius
    );
    gradient.addColorStop(0, 'rgba(247, 42, 138, 0.8)');
    gradient.addColorStop(1, 'rgba(15, 247, 239, 0.4)');
    
    visualizerContext.fillStyle = gradient;
    visualizerContext.fill();
}

/**
 * Initialize particles for particle visualization
 */
function initParticles() {
    const particleCount = 100;
    particlesArray = [];
    
    for (let i = 0; i < particleCount; i++) {
        particlesArray.push({
            x: Math.random() * visualizerCanvas.width,
            y: Math.random() * visualizerCanvas.height,
            size: Math.random() * 3 + 1,
            color: `hsl(${Math.random() * 360}, 100%, 60%)`,
            speedX: Math.random() * 2 - 1,
            speedY: Math.random() * 2 - 1
        });
    }
}

/**
 * Draw a particle-based visualization
 */
function drawParticles() {
    if (!particlesArray.length) {
        initParticles();
    }
    
    // Get average amplitude
    const averageAmplitude = dataArray.reduce((a, b) => a + b, 0) / bufferLength / 255;
    
    // Update and draw particles
    for (let i = 0; i < particlesArray.length; i++) {
        const particle = particlesArray[i];
        
        // Apply audio reactivity
        const speed = 1 + averageAmplitude * 5;
        
        // Update position
        particle.x += particle.speedX * speed;
        particle.y += particle.speedY * speed;
        
        // Wrap around edges
        if (particle.x > visualizerCanvas.width) particle.x = 0;
        if (particle.x < 0) particle.x = visualizerCanvas.width;
        if (particle.y > visualizerCanvas.height) particle.y = 0;
        if (particle.y < 0) particle.y = visualizerCanvas.height;
        
        // Draw particle
        visualizerContext.beginPath();
        const particleSize = particle.size * (1 + averageAmplitude * 2);
        visualizerContext.arc(particle.x, particle.y, particleSize, 0, Math.PI * 2);
        visualizerContext.fillStyle = particle.color;
        visualizerContext.shadowBlur = 15;
        visualizerContext.shadowColor = particle.color;
        visualizerContext.fill();
    }
    
    // Connect particles
    visualizerContext.lineWidth = 1;
    for (let i = 0; i < particlesArray.length; i++) {
        for (let j = i; j < particlesArray.length; j++) {
            const dx = particlesArray[i].x - particlesArray[j].x;
            const dy = particlesArray[i].y - particlesArray[j].y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            
            if (distance < 100) {
                visualizerContext.beginPath();
                visualizerContext.strokeStyle = `rgba(15, 247, 239, ${(1 - distance / 100) * averageAmplitude})`;
                visualizerContext.moveTo(particlesArray[i].x, particlesArray[i].y);
                visualizerContext.lineTo(particlesArray[j].x, particlesArray[j].y);
                visualizerContext.stroke();
            }
        }
    }
}

/**
 * Change the visualization type
 * @param {string} type - Type of visualization ('waveform', 'bars', 'circles', 'particles')
 */
function setVisualizationType(type) {
    visualizationType = type;
    
    // Initialize particles if switching to particle visualization
    if (type === 'particles') {
        initParticles();
    }
}

// Makes the functions available globally
window.initVisualizer = initVisualizer;
window.setVisualizationType = setVisualizationType;