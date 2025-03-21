<?php
/**
 * Pages Controller
 * Handles static pages and homepage
 */
class Pages extends Controller {
    public function __construct() {
        // Load models if needed
    }

    // Homepage
    public function index() {
        // Check if user is logged in
        if (isLoggedIn()) {
            // Redirect to dashboard
            redirect('music/dashboard');
        }

        // Page data
        $data = [
            'title' => 'Octaverum AI - Create Music with Artificial Intelligence',
            'description' => 'Generate professional-quality music with AI. Choose genres, customize parameters, and bring your musical ideas to life.',
            'additionalStyles' => ['/public/css/hero-animation.css'],
            'features' => [
                [
                    'title' => 'AI Music Generation',
                    'description' => 'Create unique music tracks in seconds using advanced AI.',
                    'icon' => 'music-note'
                ],
                [
                    'title' => 'Customizable Parameters',
                    'description' => 'Fine-tune your creations with BPM, key, genre, and more.',
                    'icon' => 'sliders'
                ],
                [
                    'title' => 'Personal Library',
                    'description' => 'Save and organize your generated music in your personal library.',
                    'icon' => 'library'
                ]
            ],
            'pricing' => [
                [
                    'name' => 'Free',
                    'price' => '0₺',
                    'features' => [
                        'Create 10 songs per month',
                        'Standard quality audio',
                        'Basic parameters control',
                        'Download MP3 files'
                    ]
                ],
                [
                    'name' => 'Premium',
                    'price' => '99.90₺/month',
                    'features' => [
                        'Create 50 songs per month',
                        'High quality audio',
                        'Advanced parameters control',
                        'AI-generated lyrics',
                        'Export to multiple formats'
                    ]
                ],
                [
                    'name' => 'Professional',
                    'price' => '199.90₺/month',
                    'features' => [
                        'Unlimited music generation',
                        'Studio quality audio',
                        'Full parameters control',
                        'AI-generated lyrics',
                        'Commercial usage license',
                        'Priority processing'
                    ]
                ]
            ]
        ];

        $this->view('pages/index', $data);
    }

    // About page
    public function about() {
        $data = [
            'title' => 'About Octaverum AI',
            'description' => 'Learn about our AI music generation platform and the technology behind it.'
        ];

        $this->view('pages/about', $data);
    }

    // Pricing page
    public function pricing() {
        $data = [
            'title' => 'Pricing - Octaverum AI',
            'description' => 'Explore our subscription plans and find the perfect one for your creative needs.',
            'plans' => [
                [
                    'name' => 'Free',
                    'price' => '0₺',
                    'monthly_limit' => FREE_GENERATION_LIMIT,
                    'quality' => 'Standard',
                    'features' => [
                        'Create 10 songs per month',
                        'Standard quality audio',
                        'Basic parameters control',
                        'Download MP3 files'
                    ]
                ],
                [
                    'name' => 'Premium',
                    'price' => PREMIUM_PRICE . '₺/month',
                    'monthly_limit' => PREMIUM_GENERATION_LIMIT,
                    'quality' => 'High',
                    'features' => [
                        'Create 50 songs per month',
                        'High quality audio',
                        'Advanced parameters control',
                        'AI-generated lyrics',
                        'Export to multiple formats'
                    ]
                ],
                [
                    'name' => 'Professional',
                    'price' => PRO_PRICE . '₺/month',
                    'monthly_limit' => 'Unlimited',
                    'quality' => 'Studio',
                    'features' => [
                        'Unlimited music generation',
                        'Studio quality audio',
                        'Full parameters control',
                        'AI-generated lyrics',
                        'Commercial usage license',
                        'Priority processing'
                    ]
                ]
            ]
        ];

        $this->view('pages/pricing', $data);
    }

    // Contact page
    public function contact() {
        // Check if form is submitted
        if ($this->isPost()) {
            // Process the contact form
            $data = $this->getSanitizedPostData();
            
            // Validate form data
            $errors = [];
            
            if (empty($data['name'])) {
                $errors['name'] = __('error_required', 'This field is required');
            }
            
            if (empty($data['email'])) {
                $errors['email'] = __('error_required', 'This field is required');
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = __('error_invalid_email', 'Please enter a valid email address');
            }
            
            if (empty($data['subject'])) {
                $errors['subject'] = __('error_required', 'This field is required');
            }
            
            if (empty($data['message'])) {
                $errors['message'] = __('error_required', 'This field is required');
            }
            
            if (empty($data['inquiry_type']) || !in_array($data['inquiry_type'], ['general', 'technical', 'billing', 'feature', 'other'])) {
                $errors['inquiry_type'] = __('error_required', 'This field is required');
            }
            
            if (empty($data['privacy'])) {
                $errors['privacy'] = __('error_privacy_required', 'You must agree to our Privacy Policy');
            }
            
            if (empty($errors)) {
                // TODO: Implement email sending
                
                // Set flash message
                flash('contact_success', __('contact_message_sent', 'Your message has been sent. We will get back to you soon.'), 'alert alert-success');
                redirect('pages/contact');
            } else {
                // Return with errors if validation failed
                $viewData = [
                    'title' => 'Contact Us - Octaverum AI',
                    'description' => 'Get in touch with our team for questions, feedback, or support.',
                    'errors' => $errors,
                    'formData' => $data  // Return the submitted data to refill the form
                ];
                $this->view('pages/contact', $viewData);
                return;
            }
        }

        $data = [
            'title' => 'Contact Us - Octaverum AI',
            'description' => 'Get in touch with our team for questions, feedback, or support.',
            'errors' => [],
            'formData' => [
                'name' => '',
                'email' => '',
                'subject' => '',
                'message' => '',
                'inquiry_type' => ''
            ]
        ];

        $this->view('pages/contact', $data);
    }

    // Terms page
    public function terms() {
        $data = [
            'title' => 'Terms of Service - Octaverum AI',
            'description' => 'Read our terms of service.'
        ];

        $this->view('pages/terms', $data);
    }

    // Privacy policy page
    public function privacy() {
        $data = [
            'title' => 'Privacy Policy - Octaverum AI',
            'description' => 'Read our privacy policy.'
        ];

        $this->view('pages/privacy', $data);
    }
    // Copyright page
    public function copyright() {
        $data = [
            'title' => 'Copyright Notice - Octaverum AI',
            'description' => 'Read our copyright information.'
        ];

        $this->view('pages/copyright', $data);
    }
    
    // FAQ page
    public function faq() {
        // Check current language
        $currentLang = getCurrentLanguage();
        
        // Define FAQs based on language
        if ($currentLang === 'tr') {
            // Turkish FAQs
            $faqs = [
                [
                    'question' => 'Octaverum AI nedir?',
                    'answer' => 'Octaverum AI, yapay zeka tarafından desteklenen gelişmiş bir müzik oluşturma platformudur. Platformumuz, kullanıcıların basit metin komutları ve parametre ayarlarıyla çeşitli türlerde ve stillerde profesyonel kalitede müzik parçaları oluşturmasına olanak tanır.'
                ],
                [
                    'question' => 'Yapay zeka müzik üretimi nasıl çalışır?',
                    'answer' => 'Yapay zeka sistemimiz, müzikal kalıpları, düzenlemeleri ve stilleri anlamak için milyonlarca müzik parçası üzerinde eğitilmiştir. Bir metin isteği sağladığınızda ve parametreleri seçtiğinizde, yapay zeka isteğinizi analiz eder ve belirtimlerinize uygun benzersiz bir müzik kompozisyonu oluşturur.'
                ],
                [
                    'question' => 'Octaverum AI ile oluşturulan müzik telifsiz midir?',
                    'answer' => 'Lisanslama, abonelik katmanınıza bağlıdır. Ücretsiz plan kullanıcıları oluşturulan müziği yalnızca kişisel, ticari olmayan amaçlar için kullanabilir. Premium kullanıcılar sınırlı ticari kullanım hakları elde eder. Profesyonel plan aboneleri, müziği herhangi bir projede kullanmak için tam ticari haklara sahip olur.'
                ],
                [
                    'question' => 'Kaç parça üretebilirim?',
                    'answer' => 'Ücretsiz kullanıcılar ayda 10 parçaya kadar oluşturabilir. Premium aboneler aylık 50 parça oluşturabilir. Profesyonel plan kullanıcıları sınırsız müzik oluşturma imkanına sahiptir.'
                ],
                [
                    'question' => 'İndirme için hangi dosya formatları mevcut?',
                    'answer' => 'Ücretsiz kullanıcılar parçaları MP3 formatında indirebilir. Premium ve Profesyonel kullanıcılar, daha fazla düzenleme için WAV, FLAC ve stem\'ler dahil olmak üzere ek formatlara erişebilir.'
                ],
                [
                    'question' => 'Müziği oluşturduktan sonra düzenleyebilir miyim?',
                    'answer' => 'Evet, abonelik katmanınıza bağlı olarak. Premium ve Profesyonel kullanıcılar, tempo ayarlamaları, anahtar değişiklikleri ve enstrüman miksajı dahil olmak üzere daha gelişmiş düzenleme özelliklerine erişebilir. Profesyonel kullanıcılar ayrıca harici yazılımlarda detaylı düzenleme için bireysel stem\'leri indirebilir.'
                ],
                [
                    'question' => 'Octaverum AI hangi müzik türlerini oluşturabilir?',
                    'answer' => 'Yapay zekamız, Elektronik, Pop, Rock, Hip Hop, Klasik, Caz, Ambient, Sinematik ve birçok füzyon stili dahil olmak üzere çok çeşitli türlerde müzik üretebilir. Oluşturma isteklerinizde tür tercihlerinizi belirtebilirsiniz.'
                ],
                [
                    'question' => 'Oluşturulan parçalar ne kadar uzunlukta?',
                    'answer' => 'Ücretsiz plan kullanıcıları 30 saniyelik önizlemeler oluşturabilir. Premium kullanıcılar 3 dakikaya kadar parçalar oluşturabilir. Profesyonel aboneler 5 dakikaya kadar müzik oluşturabilir.'
                ],
                [
                    'question' => 'Octaverum AI\'yı ticari projeler için kullanabilir miyim?',
                    'answer' => 'Evet, ancak ticari kullanım hakları abonelik katmanınıza bağlıdır. Profesyonel plan aboneleri, para kazandırılan içerik, reklamlar ve ticari ürünler dahil olmak üzere oluşturulan müziği herhangi bir projede kullanmak için tam ticari haklara sahiptir.'
                ],
                [
                    'question' => 'Belirli stiller veya sanatçı ilhamları isteyebilir miyim?',
                    'answer' => 'Evet, oluşturma isteklerinizde belirli stiller, ruh halleri veya tür referansları belirtebilirsiniz. Ancak, özgünlüğü sağlamak için tam olarak belirli sanatçılar gibi ses çıkaran parçalar istemek yerine istenilen müzikal öğeleri tanımlamaya odaklanmanızı öneririz.'
                ],
                [
                    'question' => 'Hangi ödeme yöntemlerini kabul ediyorsunuz?',
                    'answer' => 'Bölgenize bağlı olarak tüm büyük kredi kartlarını, PayPal\'ı ve çeşitli yerel ödeme yöntemlerini kabul ediyoruz. Tüm ödemeler ödeme ortaklarımız aracılığıyla güvenli bir şekilde işlenir.'
                ],
                [
                    'question' => 'Aboneliğimi istediğim zaman iptal edebilir miyim?',
                    'answer' => 'Evet, aboneliğinizi hesap ayarlarınızdan istediğiniz zaman iptal edebilirsiniz. Aboneliğiniz mevcut fatura döneminin sonuna kadar aktif kalacaktır.'
                ],
                [
                    'question' => 'Mobil uygulama mevcut mu?',
                    'answer' => 'Şu anda Octaverum AI, hem masaüstü hem de mobil tarayıcılar için optimize edilmiş bir web uygulaması olarak mevcuttur. iOS ve Android için özel mobil uygulamalar geliştirme aşamasındadır ve yakında piyasaya sürülecektir.'
                ],
                [
                    'question' => 'Yapay zeka tarafından oluşturulan müziğin kalitesi nasıl?',
                    'answer' => 'Octaverum AI, profesyonel olarak oluşturulmuş gibi duran yüksek kaliteli müzik üretir. Premium ve Profesyonel plan aboneleri, ticari kullanım için uygun daha yüksek ses kalitesi seçeneklerine erişebilir.'
                ],
                [
                    'question' => 'İade sunuyor musunuz?',
                    'answer' => 'Abonelik ödemeleri için genellikle iade sunmuyoruz, ancak gelecekteki ücretleri önlemek için istediğiniz zaman iptal edebilirsiniz. Faturalandırma veya hizmet sorunlarıyla ilgili özel endişeleriniz için lütfen destek ekibimizle iletişime geçin.'
                ]
            ];
        } else {
            // English FAQs (default)
            $faqs = [
                [
                    'question' => 'What is Octaverum AI?',
                    'answer' => 'Octaverum AI is an advanced music generation platform powered by artificial intelligence. Our platform allows users to create professional-quality music tracks in various genres and styles with simple text prompts and parameter adjustments.'
                ],
                [
                    'question' => 'How does AI music generation work?',
                    'answer' => 'Our AI system has been trained on millions of music tracks to understand musical patterns, arrangements, and styles. When you provide a text prompt and select parameters, the AI analyzes your request and generates a unique musical composition that matches your specifications.'
                ],
                [
                    'question' => 'Is the music created with Octaverum AI royalty-free?',
                    'answer' => 'The licensing depends on your subscription tier. Free plan users can use generated music for personal, non-commercial purposes only. Premium users get limited commercial usage rights. Professional plan subscribers receive full commercial rights to use the music in any project.'
                ],
                [
                    'question' => 'How many tracks can I generate?',
                    'answer' => 'Free users can generate up to 10 tracks per month. Premium subscribers can create 50 tracks monthly. Professional plan users enjoy unlimited music generation.'
                ],
                [
                    'question' => 'What file formats are available for download?',
                    'answer' => 'Free users can download tracks in MP3 format. Premium and Professional users can access additional formats including WAV, FLAC, and stems for further editing.'
                ],
                [
                    'question' => 'Can I edit the music after generation?',
                    'answer' => 'Yes, depending on your subscription tier. Premium and Professional users can access more advanced editing features, including tempo adjustments, key changes, and instrument mixing. Professional users can also download individual stems for detailed editing in external software.'
                ],
                [
                    'question' => 'What genres of music can Octaverum AI create?',
                    'answer' => 'Our AI can generate music across a wide range of genres including but not limited to: Electronic, Pop, Rock, Hip Hop, Classical, Jazz, Ambient, Cinematic, and many fusion styles. You can specify genre preferences in your generation prompts.'
                ],
                [
                    'question' => 'How long are the generated tracks?',
                    'answer' => 'Free plan users can generate 30-second previews. Premium users can create tracks up to 3 minutes long. Professional subscribers can generate music up to 5 minutes in length.'
                ],
                [
                    'question' => 'Can I use Octaverum AI for commercial projects?',
                    'answer' => 'Yes, but the commercial usage rights depend on your subscription tier. Professional plan subscribers have full commercial rights to use generated music in any project, including monetized content, advertisements, and commercial products.'
                ],
                [
                    'question' => 'Can I request specific styles or artist inspirations?',
                    'answer' => 'Yes, you can mention specific styles, moods, or genre references in your generation prompts. However, we recommend focusing on describing the desired musical elements rather than requesting tracks that sound exactly like specific artists to ensure originality.'
                ],
                [
                    'question' => 'What payment methods do you accept?',
                    'answer' => 'We accept all major credit cards, PayPal, and various local payment methods depending on your region. All payments are processed securely through our payment partners.'
                ],
                [
                    'question' => 'Can I cancel my subscription anytime?',
                    'answer' => 'Yes, you can cancel your subscription at any time from your account settings. Your subscription will remain active until the end of the current billing period.'
                ],
                [
                    'question' => 'Is there a mobile app available?',
                    'answer' => 'Currently, Octaverum AI is available as a web application optimized for both desktop and mobile browsers. Dedicated mobile apps for iOS and Android are in development and will be released soon.'
                ],
                [
                    'question' => 'How is the quality of AI-generated music?',
                    'answer' => 'Octaverum AI produces high-quality music that sounds professionally created. Premium and Professional plan subscribers have access to even higher audio quality options suitable for commercial use.'
                ],
                [
                    'question' => 'Do you offer refunds?',
                    'answer' => 'We don\'t typically offer refunds for subscription payments, but you can cancel anytime to prevent future charges. For specific concerns about billing or service issues, please contact our support team.'
                ]
            ];
        }
        
        $data = [
            'title' => 'FAQ - Octaverum AI',
            'description' => 'Find answers to common questions about Octaverum AI music generation platform.',
            'faqs' => $faqs
        ];

        $this->view('pages/faq', $data);
    }

    // Error 404 page
    public function error404() {
        $data = [
            'title' => 'Page Not Found - Octaverum AI',
            'description' => 'The page you are looking for does not exist.'
        ];

        $this->view('pages/404', $data);
    }
    
    // System diagnostics page
    public function diagnostics() {
        $data = [
            'title' => 'System Diagnostics - Octaverum AI',
            'description' => 'System health check and diagnostics information'
        ];

        $this->view('pages/diagnostics', $data);
    }
}