<?php
/**
 * Users Controller
 * Handles user authentication and account management
 */
class Users extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    // Register new user
    public function register() {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('music/dashboard');
        }
        
        // Check for POST
            if ($this->isPost()) {
                // Process form
                $data = $this->getSanitizedPostData();
                
                // Initialize post data with empty values in case of validation failure
                if (!isset($data['username'])) $data['username'] = '';
                if (!isset($data['email'])) $data['email'] = '';
                if (!isset($data['password'])) $data['password'] = '';
                if (!isset($data['confirm_password'])) $data['confirm_password'] = '';
                
                // Validate input
                $errors = [];
            
            // Validate username
            if (empty($data['username'])) {
                $errors['username'] = 'Please enter a username';
            } elseif (strlen($data['username']) < 3) {
                $errors['username'] = 'Username must be at least 3 characters';
            } elseif ($this->userModel->findUserByUsername($data['username'])) {
                $errors['username'] = 'Username is already taken';
            }
            
            // Validate email
            if (empty($data['email'])) {
                $errors['email'] = 'Please enter an email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Please enter a valid email';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $errors['email'] = 'Email is already registered';
            }
            
            // Validate password
            if (empty($data['password'])) {
                $errors['password'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }
            
            // Validate confirm password
            if (empty($data['confirm_password'])) {
                $errors['confirm_password'] = 'Please confirm password';
            } elseif ($data['password'] != $data['confirm_password']) {
                $errors['confirm_password'] = 'Passwords do not match';
            }
            
            // Make sure errors are empty
            if (empty($errors)) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Set default subscription tier
                $data['subscription_tier'] = 'free';
                $data['monthly_generations'] = 0;
                $data['reset_date'] = date('Y-m-d', strtotime('+1 month'));
                
                // Register user
                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are registered and can now log in');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/register', [
                    'title' => 'Register - Octaverum AI',
                    'description' => 'Create an account to start generating music with AI.',
                    'errors' => $errors,
                    'username' => $data['username'],
                    'email' => $data['email']
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Register - Octaverum AI',
                'description' => 'Create an account to start generating music with AI.',
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'errors' => []
            ];
            
            // Load view
            $this->view('users/register', $data);
        }
    }

    // User login
    public function login() {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('music/dashboard');
        }
        
        // Check for POST
            if ($this->isPost()) {
                // Process form
                $data = $this->getSanitizedPostData();
                
                // Initialize errors array
                $errors = [];
                
                // Initialize errors array
                $errors = [];
                
                // Validate email
                if (empty($data['email'])) {
                    $errors['email'] = 'Please enter your email';
                }
                
                // Validate password
                if (empty($data['password'])) {
                    $errors['password'] = 'Please enter your password';
                }
                
                // Check for user/email
                if (empty($errors)) {
                $user = $this->userModel->findUserByEmail($data['email']);
                
                if ($user) {
                    // User found, check password
                    if (password_verify($data['password'], $user->password)) {
                        // Create session
                        $this->createUserSession($user);
                    } else {
                        $errors['password'] = 'Password incorrect';
                        $this->view('users/login', [
                            'title' => 'Login - Octaverum AI',
                            'description' => 'Log in to your Octaverum AI account.',
                            'errors' => $errors,
                            'email' => $data['email']
                        ]);
                    }
                } else {
                    $errors['email'] = 'No user found with that email';
                    $this->view('users/login', [
                        'title' => 'Login - Octaverum AI',
                        'description' => 'Log in to your Octaverum AI account.',
                        'errors' => $errors,
                        'email' => $data['email']
                    ]);
                }
            } else {
                $this->view('users/login', [
                    'title' => 'Login - Octaverum AI',
                    'description' => 'Log in to your Octaverum AI account.',
                    'errors' => $errors,
                    'email' => $data['email'] ?? ''
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Login - Octaverum AI',
                'description' => 'Log in to your Octaverum AI account.',
                'email' => '',
                'password' => '',
                'errors' => []
            ];
            
            // Load view
            $this->view('users/login', $data);
        }
    }

    // Create user session
    private function createUserSession($user) {
        // Remove password from session data
        unset($user->password);
        
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_data'] = $user;
        
        // Check if monthly reset is needed
        $resetDate = new DateTime($user->reset_date);
        $today = new DateTime();
        
        if ($today >= $resetDate) {
            // Reset monthly generations count
            $this->userModel->resetMonthlyGenerations($user->id);
            $_SESSION['user_data']->monthly_generations = 0;
            
            // Set new reset date
            $newResetDate = $today->modify('+1 month')->format('Y-m-d');
            $this->userModel->updateResetDate($user->id, $newResetDate);
            $_SESSION['user_data']->reset_date = $newResetDate;
        }
        
        // Redirect to subscription page instead of dashboard
        redirect('users/subscription');
    }

    // User logout
    public function logout() {
        // Unset session variables
        unset($_SESSION['user_id']);
        unset($_SESSION['user_data']);
        
        // Destroy session
        session_destroy();
        
        redirect('users/login');
    }
// User profile
public function profile() {
    // Check if user is logged in
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    // Check for POST
    if ($this->isPost()) {
        // Process form
        $data = $this->getSanitizedPostData();
        
        // Validate input
        $errors = [];
        
        // Validate name
        if (empty($data['name'])) {
            $errors['name'] = 'Please enter your name';
        }
        
        // Validate email - only if changed
        if ($data['email'] != $_SESSION['user_data']->email) {
            if (empty($data['email'])) {
                $errors['email'] = 'Please enter an email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Please enter a valid email';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $errors['email'] = 'Email is already registered';
            }
        }
        
        // Validate website if provided
        if (!empty($data['website'])) {
            if (!filter_var($data['website'], FILTER_VALIDATE_URL)) {
                $errors['website'] = 'Please enter a valid URL';
            }
        }
        
        // Check for password change
        if (!empty($data['current_password']) || !empty($data['new_password']) || !empty($data['confirm_new_password'])) {
            // Check current password
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            if (!password_verify($data['current_password'], $user->password)) {
                $errors['current_password'] = 'Current password is incorrect';
            }
            
            // Validate new password
            if (empty($data['new_password'])) {
                $errors['new_password'] = 'Please enter a new password';
            } elseif (strlen($data['new_password']) < 6) {
                $errors['new_password'] = 'Password must be at least 6 characters';
            }
            
            // Validate confirm new password
            if (empty($data['confirm_new_password'])) {
                $errors['confirm_new_password'] = 'Please confirm new password';
            } elseif ($data['new_password'] != $data['confirm_new_password']) {
                $errors['confirm_new_password'] = 'Passwords do not match';
            }
        }
        
        // Process avatar upload from base64 string
        if (!empty($data['avatar']) && strpos($data['avatar'], 'data:image') === 0) {
            $avatarData = $data['avatar'];
            // Extract the base64 encoded binary data
            list(, $avatarData) = explode(',', $avatarData);
            $avatarData = base64_decode($avatarData);
            
            if ($avatarData) {
                // Create directory if it doesn't exist
                $uploadDir = ROOTPATH . '/public/uploads/avatars/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Generate unique filename
                $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.jpg';
                $filepath = $uploadDir . $filename;
                
                // Save the image
                if (file_put_contents($filepath, $avatarData)) {
                    $data['avatar'] = '/public/uploads/avatars/' . $filename;
                } else {
                    $errors['avatar'] = 'Failed to save avatar image';
                }
            } else {
                $errors['avatar'] = 'Invalid avatar image data';
            }
        }
        
        // Make sure errors are empty
        if (empty($errors)) {
            // Update user data
            $updateData = [
                'id' => $_SESSION['user_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'bio' => $data['bio'] ?? '',
                'theme' => $data['theme'] ?? 'dark'
            ];
            
            // Add optional fields if they exist
            if (isset($data['website'])) {
                $updateData['website'] = $data['website'];
            }
            
            if (isset($data['location'])) {
                $updateData['location'] = $data['location'];
            }
            
            // Add social media links if provided
            if (isset($data['social_twitter'])) {
                $updateData['social_twitter'] = $data['social_twitter'];
            }
            
            if (isset($data['social_instagram'])) {
                $updateData['social_instagram'] = $data['social_instagram'];
            }
            
            if (isset($data['social_soundcloud'])) {
                $updateData['social_soundcloud'] = $data['social_soundcloud'];
            }
            
            if (isset($data['social_spotify'])) {
                $updateData['social_spotify'] = $data['social_spotify'];
            }
            
            // Add appearance settings if provided
            if (isset($data['color_scheme'])) {
                $updateData['color_scheme'] = $data['color_scheme'];
            }
            
            if (isset($data['enable_animations'])) {
                $updateData['enable_animations'] = !empty($data['enable_animations']) ? 1 : 0;
            }
            
            if (isset($data['enable_visualizer'])) {
                $updateData['enable_visualizer'] = !empty($data['enable_visualizer']) ? 1 : 0;
            }
            
            if (isset($data['dashboard_layout'])) {
                $updateData['dashboard_layout'] = $data['dashboard_layout'];
            }
            
            // Add security settings if provided
            if (isset($data['enable_2fa'])) {
                $updateData['enable_2fa'] = !empty($data['enable_2fa']) ? 1 : 0;
            }
            
            if (isset($data['notify_login'])) {
                $updateData['notify_login'] = !empty($data['notify_login']) ? 1 : 0;
            }
            
            // Update password if changed
            if (!empty($data['new_password'])) {
                $updateData['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
            }
            
            // Update avatar if uploaded
            if (isset($data['avatar']) && strpos($data['avatar'], '/public/uploads/avatars/') === 0) {
                $updateData['avatar'] = $data['avatar'];
            }
            
            // Update user
            if ($this->userModel->updateUser($updateData)) {
                // Update session data
                $user = $this->userModel->getUserById($_SESSION['user_id']);
                unset($user->password);
                $_SESSION['user_data'] = $user;
                
                flash('profile_success', 'Profile updated successfully');
                redirect('users/profile');
            } else {
                die('Something went wrong');
            }
        } else {
            // Load view with errors
            $this->view('users/profile', [
                'title' => 'Profile - Octaverum AI',
                'description' => 'Manage your Octaverum AI profile.',
                'errors' => $errors,
                'user' => $_SESSION['user_data'],
                'additionalScripts' => [
                    '/public/js/profile.js'
                ],
                'additionalStyles' => [
                    '/public/css/profile.css'
                ]
            ]);
        }
    } else {
        // Init data
        $data = [
            'title' => 'Profile - Octaverum AI',
            'description' => 'Manage your Octaverum AI profile.',
            'user' => $_SESSION['user_data'],
            'errors' => [],
            'additionalScripts' => [
                '/public/js/profile.js'
            ],
            'additionalStyles' => [
                '/public/css/profile.css'
            ]
        ];
        
        // Load view
        $this->view('users/profile', $data);
    }
}

    // Subscription management
    public function subscription() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Load language file
        $currentLanguage = getCurrentLanguage();
        $langFile = APPROOT . '/languages/' . $currentLanguage . '.php';
        if (file_exists($langFile)) {
            $langText = require $langFile;
        } else {
            // Fallback to English
            $langText = require APPROOT . '/languages/en.php';
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            
            // Update subscription
            $updateData = [
                'id' => $_SESSION['user_id'],
                'subscription_tier' => $data['subscription_tier']
            ];
            
            // TODO: Process payment for premium/professional tiers
            
            // Update user
            if ($this->userModel->updateSubscription($updateData)) {
                // Update session data
                $user = $this->userModel->getUserById($_SESSION['user_id']);
                unset($user->password);
                $_SESSION['user_data'] = $user;
                
                flash('subscription_success', 'Subscription updated successfully');
                redirect('users/subscription');
            } else {
                // If there's an error, load the view with error message and language text
                $data = [
                    'title' => 'Subscription - Octaverum AI',
                    'description' => 'Manage your Octaverum AI subscription.',
                    'user' => $_SESSION['user_data'],
                    'langText' => $langText,
                    'errors' => ['update' => 'Failed to update subscription. Please try again.']
                ];
                $this->view('users/subscription', $data);
            }
        } else {
            // Load language file
                    $currentLanguage = getCurrentLanguage();
                    $langFile = APPROOT . '/languages/' . $currentLanguage . '.php';
                    if (file_exists($langFile)) {
                        $langText = require $langFile;
                    } else {
                        // Fallback to English
                        $langText = require APPROOT . '/languages/en.php';
                    }
        
                    // Init data
                    $data = [
                        'title' => 'Subscription - Octaverum AI',
                        'description' => 'Manage your Octaverum AI subscription.',
                        'user' => $_SESSION['user_data'],
                        'langText' => $langText,
                        'plans' => [
                            [
                                'id' => 'free',
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
                                'id' => 'premium',
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
                                'id' => 'professional',
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
                        ],
                        'errors' => []
                    ];
            
            // Load view
            $this->view('users/subscription', $data);
        }
    }

    // Password reset request
    public function forgotPassword() {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('music/dashboard');
        }
        
        // Check for POST
            if ($this->isPost()) {
                // Process form
                $data = $this->getSanitizedPostData();
                if (!isset($data['email'])) $data['email'] = '';
                
                // Initialize errors array
                $errors = [];
                
                // Validate email
                if (empty($data['email'])) {
                    $errors['email'] = 'Please enter your email';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Please enter a valid email';
                } else {
                    $user = $this->userModel->findUserByEmail($data['email']);
                    if (!$user) {
                        $errors['email'] = 'No user found with that email';
                    }
                }
                
                // Make sure errors are empty
                if (empty($errors)) {
                // Generate token
                $token = bin2hex(random_bytes(32));
                
                // Save token
                if ($this->userModel->saveResetToken($data['email'], $token)) {
                    // TODO: Send email with reset link
                    
                    // Show success message
                    flash('reset_message', 'Password reset instructions have been sent to your email');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/forgotPassword', [
                    'title' => 'Forgot Password - Octaverum AI',
                    'description' => 'Reset your Octaverum AI account password.',
                    'errors' => $errors,
                    'email' => $data['email']
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Forgot Password - Octaverum AI',
                'description' => 'Reset your Octaverum AI account password.',
                'email' => '',
                'errors' => []
            ];
            
            // Load view
            $this->view('users/forgotPassword', $data);
        }
    }

    // Switch language
    public function switchLanguage($lang = '') {
        // Validate language
        $available_languages = ['en', 'tr'];
        if (!in_array($lang, $available_languages)) {
            $lang = 'en';
        }
        
        // If user is logged in, update their preference in the database
        if (isLoggedIn()) {
            $this->userModel->updateUserLanguage($_SESSION['user_id'], $lang);
            $_SESSION['user_data']->language = $lang;
        } else {
            // For guests, just store in session
            $_SESSION['language'] = $lang;
        }
        
        // Redirect back to previous page
        $redirect = $_SERVER['HTTP_REFERER'] ?? URL_ROOT;
        redirect($redirect);
    }
    
    // Reset password
    public function resetPassword($token = '') {
        // Check if user is already logged in
        if (isLoggedIn()) {
            redirect('music/dashboard');
        }
        
        // Check if token is valid
        if (empty($token)) {
            redirect('users/login');
        }
        
        $user = $this->userModel->findUserByResetToken($token);
        if (!$user) {
            flash('reset_error', 'Invalid or expired reset token', 'alert alert-danger');
            redirect('users/login');
        }
        
        // Check for POST
        if ($this->isPost()) {
            // Process form
            $data = $this->getSanitizedPostData();
            if (!isset($data['password'])) $data['password'] = '';
            if (!isset($data['confirm_password'])) $data['confirm_password'] = '';
            
            // Initialize errors array
            $errors = [];
            
            // Validate passwords
            if (empty($data['password'])) {
                $errors['password'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }
            
            if (empty($data['confirm_password'])) {
                $errors['confirm_password'] = 'Please confirm password';
            } elseif ($data['password'] != $data['confirm_password']) {
                $errors['confirm_password'] = 'Passwords do not match';
            }
            
            // Make sure errors are empty
            if (empty($errors)) {
                // Reset password
                if ($this->userModel->resetPassword($user->id, password_hash($data['password'], PASSWORD_DEFAULT))) {
                    flash('password_success', 'Password changed successfully. You can now login with your new password.');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/resetPassword', [
                    'title' => 'Reset Password - Octaverum AI',
                    'description' => 'Set a new password for your Octaverum AI account.',
                    'errors' => $errors,
                    'token' => $token
                ]);
            }
        } else {
            // Init data
            $data = [
                'title' => 'Reset Password - Octaverum AI',
                'description' => 'Set a new password for your Octaverum AI account.',
                'token' => $token,
                'errors' => []
            ];
            
            // Load view
            $this->view('users/resetPassword', $data);
        }
    }
}