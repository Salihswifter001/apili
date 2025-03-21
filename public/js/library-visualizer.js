/**
 * Octaverum AI - Kütüphane Görselleştirici
 * Müzik kütüphanesi sayfası için özel gelişmiş animasyonlu görselleştirici
 */

class LibraryVisualizer {
    constructor(canvasId) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;
        
        this.ctx = this.canvas.getContext('2d');
        this.isActive = true;
        this.particles = [];
        this.maxParticles = 100;
        this.wavePoints = [];
        this.wavePointCount = 128;
        this.waveAmplitude = 30;
        this.waveSpeed = 0.03;
        this.genreColors = {
            'electronic': {
                color: '#00B7FF',
                rgb: [0, 183, 255]
            },
            'pop': {
                color: '#FF69B4',
                rgb: [255, 105, 180]
            },
            'rock': {
                color: '#FF3D00',
                rgb: [255, 61, 0]
            },
            'hip-hop': {
                color: '#8A2BE2',
                rgb: [138, 43, 226]
            },
            'jazz': {
                color: '#FFD700',
                rgb: [255, 215, 0]
            },
            'classical': {
                color: '#008080',
                rgb: [0, 128, 128]
            },
            'ambient': {
                color: '#ADD8E6',
                rgb: [173, 216, 230]
            },
            'folk': {
                color: '#8B4513',
                rgb: [139, 69, 19]
            },
            'metal': {
                color: '#2F4F4F',
                rgb: [47, 79, 79]
            },
            'funk': {
                color: '#FF8C00',
                rgb: [255, 140, 0]
            },
            'blues': {
                color: '#000080',
                rgb: [0, 0, 128]
            }
        };
        
        // Tüm müzik türlerini topla
        this.genres = Object.keys(this.genreColors);
        
        // Pencere yeniden boyutlandırıldığında canvas'ı güncelle
        window.addEventListener('resize', this.resizeCanvas.bind(this));
        
        // Canvas'ı başlat
        this.resizeCanvas();
        this.initWavePoints();
        this.initParticles();
        this.animate();
        
        // Etkileşim ekle
        this.canvas.addEventListener('mousemove', this.handleMouseMove.bind(this));
        this.canvas.addEventListener('mouseleave', this.handleMouseLeave.bind(this));
    }
    
    resizeCanvas() {
        this.canvas.width = this.canvas.parentElement.offsetWidth;
        this.canvas.height = this.canvas.parentElement.offsetHeight;
        
        // Dalga noktalarını yeniden başlat
        this.initWavePoints();
    }
    
    initWavePoints() {
        this.wavePoints = [];
        const width = this.canvas.width;
        const step = width / (this.wavePointCount - 1);
        
        for (let i = 0; i < this.wavePointCount; i++) {
            this.wavePoints.push({
                x: i * step,
                y: this.canvas.height / 2,
                originalY: this.canvas.height / 2,
                speed: this.waveSpeed * (Math.random() * 0.5 + 0.8), // Biraz rastgele hız
                amplitude: this.waveAmplitude * (Math.random() * 0.5 + 0.8), // Biraz rastgele genlik
                phase: Math.random() * Math.PI * 2, // Rastgele başlangıç fazı
                genre: this.genres[Math.floor(Math.random() * this.genres.length)]
            });
        }
    }
    
    initParticles() {
        this.particles = [];
        
        for (let i = 0; i < this.maxParticles; i++) {
            this.addParticle();
        }
    }
    
    addParticle() {
        const genre = this.genres[Math.floor(Math.random() * this.genres.length)];
        const color = this.genreColors[genre].color;
        const rgb = this.genreColors[genre].rgb;
        
        this.particles.push({
            x: Math.random() * this.canvas.width,
            y: Math.random() * this.canvas.height,
            size: Math.random() * 3 + 1,
            speedX: Math.random() * 0.5 - 0.25,
            speedY: Math.random() * 0.5 - 0.25,
            color: color,
            rgb: rgb,
            opacity: Math.random() * 0.5 + 0.1,
            genre: genre
        });
    }
    
    updateWavePoints(time) {
        for (let i = 0; i < this.wavePoints.length; i++) {
            const point = this.wavePoints[i];
            // Sinüs dalgası hareketi oluştur
            point.y = point.originalY + Math.sin(time * point.speed + point.phase) * point.amplitude;
        }
    }
    
    updateParticles() {
        for (let i = 0; i < this.particles.length; i++) {
            const particle = this.particles[i];
            
            // Parçacıkları hareket ettir
            particle.x += particle.speedX;
            particle.y += particle.speedY;
            
            // Ekran dışına çıkan parçacıkları sıfırla
            if (particle.x < 0) particle.x = this.canvas.width;
            if (particle.x > this.canvas.width) particle.x = 0;
            if (particle.y < 0) particle.y = this.canvas.height;
            if (particle.y > this.canvas.height) particle.y = 0;
        }
    }
    
    drawWaves() {
        if (this.wavePoints.length < 2) return;
        
        // Her tür için bir dalga çiz
        this.genres.forEach((genre, genreIndex) => {
            const color = this.genreColors[genre].color;
            const offset = (genreIndex - this.genres.length / 2) * (this.waveAmplitude / 2);
            
            this.ctx.beginPath();
            this.ctx.moveTo(0, this.canvas.height / 2 + offset);
            
            // Dalga için eğri çiz
            for (let i = 0; i < this.wavePoints.length - 1; i++) {
                const point = this.wavePoints[i];
                const nextPoint = this.wavePoints[i + 1];
                
                // Geçerli nokta bu türe aitse dalga genliğini uygula
                const currentY = point.genre === genre ? point.y + offset : this.canvas.height / 2 + offset;
                const nextY = nextPoint.genre === genre ? nextPoint.y + offset : this.canvas.height / 2 + offset;
                
                // Bezier eğrisi ile noktaları bağla
                const xc = (point.x + nextPoint.x) / 2;
                const yc = (currentY + nextY) / 2;
                this.ctx.quadraticCurveTo(point.x, currentY, xc, yc);
            }
            
            // Dalganın altını canvas tabanına bağla (dolgu için)
            this.ctx.lineTo(this.canvas.width, this.canvas.height);
            this.ctx.lineTo(0, this.canvas.height);
            this.ctx.closePath();
            
            // Dalga gradienti oluştur
            const gradient = this.ctx.createLinearGradient(0, 0, 0, this.canvas.height);
            gradient.addColorStop(0, `${color}30`); // Üst (daha şeffaf)
            gradient.addColorStop(0.5, `${color}15`); // Orta
            gradient.addColorStop(1, 'rgba(0, 0, 0, 0)'); // Alt (tamamen şeffaf)
            
            this.ctx.fillStyle = gradient;
            this.ctx.fill();
            
            // Dalga çizgisini çiz
            this.ctx.beginPath();
            this.ctx.moveTo(0, this.canvas.height / 2 + offset);
            
            for (let i = 0; i < this.wavePoints.length - 1; i++) {
                const point = this.wavePoints[i];
                const nextPoint = this.wavePoints[i + 1];
                
                const currentY = point.genre === genre ? point.y + offset : this.canvas.height / 2 + offset;
                const nextY = nextPoint.genre === genre ? nextPoint.y + offset : this.canvas.height / 2 + offset;
                
                const xc = (point.x + nextPoint.x) / 2;
                const yc = (currentY + nextY) / 2;
                this.ctx.quadraticCurveTo(point.x, currentY, xc, yc);
            }
            
            this.ctx.strokeStyle = `${color}80`; // Yarı şeffaf çizgi
            this.ctx.lineWidth = 1.5;
            this.ctx.stroke();
        });
    }
    
    drawParticles() {
        this.particles.forEach(particle => {
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            this.ctx.fillStyle = `rgba(${particle.rgb[0]}, ${particle.rgb[1]}, ${particle.rgb[2]}, ${particle.opacity})`;
            this.ctx.fill();
            
            // Parlama efekti ekle
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size * 2, 0, Math.PI * 2);
            this.ctx.fillStyle = `rgba(${particle.rgb[0]}, ${particle.rgb[1]}, ${particle.rgb[2]}, ${particle.opacity * 0.2})`;
            this.ctx.fill();
        });
    }
    
    drawVisualization(time) {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Arka plan gradienti
        const gradient = this.ctx.createLinearGradient(0, 0, this.canvas.width, this.canvas.height);
        gradient.addColorStop(0, 'rgba(0, 20, 40, 0.1)');
        gradient.addColorStop(1, 'rgba(0, 5, 15, 0.2)');
        this.ctx.fillStyle = gradient;
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Dalga ve parçacıkları güncelle
        this.updateWavePoints(time);
        this.updateParticles();
        
        // Dalga ve parçacıkları çiz
        this.drawWaves();
        this.drawParticles();
    }
    
    animate() {
        if (!this.isActive) return;
        
        const time = Date.now() * 0.001; // Saniye cinsinden zaman
        this.drawVisualization(time);
        
        // Animasyonu devam ettir
        requestAnimationFrame(this.animate.bind(this));
    }
    
    handleMouseMove(e) {
        // Fare pozisyonunu canvas koordinatlarına çevir
        const rect = this.canvas.getBoundingClientRect();
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        
        // Fare yakınındaki dalga noktalarını etkile
        for (let i = 0; i < this.wavePoints.length; i++) {
            const point = this.wavePoints[i];
            const distance = Math.sqrt(Math.pow(mouseX - point.x, 2) + Math.pow(mouseY - point.originalY, 2));
            
            if (distance < 100) {
                // Fare yakınında ise amplitüdü artır
                point.amplitude = this.waveAmplitude * 2 * (1 - distance / 100);
            } else {
                // Fare uzaktaysa normal değere dön
                point.amplitude = this.waveAmplitude * (Math.random() * 0.5 + 0.8);
            }
        }
        
        // Fare pozisyonuna yeni parçacıklar ekle
        for (let i = 0; i < 3; i++) {
            const genre = this.genres[Math.floor(Math.random() * this.genres.length)];
            const color = this.genreColors[genre].color;
            const rgb = this.genreColors[genre].rgb;
            
            this.particles.push({
                x: mouseX,
                y: mouseY,
                size: Math.random() * 4 + 2,
                speedX: (Math.random() - 0.5) * 2,
                speedY: (Math.random() - 0.5) * 2,
                color: color,
                rgb: rgb,
                opacity: Math.random() * 0.7 + 0.3,
                genre: genre,
                life: 50 // Parçacık ömrü
            });
            
            // Maksimum parçacık sayısını korumak için gerekirse en eskiyi sil
            if (this.particles.length > this.maxParticles) {
                this.particles.shift();
            }
        }
    }
    
    handleMouseLeave() {
        // Fare ayrıldığında tüm noktaları normale döndür
        for (let i = 0; i < this.wavePoints.length; i++) {
            this.wavePoints[i].amplitude = this.waveAmplitude * (Math.random() * 0.5 + 0.8);
        }
    }
    
    // Görselleştiriciyi durdur/başlat
    toggle() {
        this.isActive = !this.isActive;
        if (this.isActive) {
            this.animate();
        }
    }
}

// Sayfa yüklendiğinde görselleştiriciyi başlat
document.addEventListener('DOMContentLoaded', function() {
    const visualizer = new LibraryVisualizer('libraryVisualizer');
    
    // Sayfada görsel olmadığında performans için durdur, tekrar görünür olduğunda başlat
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            visualizer.isActive = false;
        } else {
            visualizer.isActive = true;
            visualizer.animate();
        }
    });
}); 