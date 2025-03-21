<?php
/**
 * Admin Controller
 * Veritabanı yönetimi için admin paneli kontrolcüsü
 */
class Admin extends Controller {
    private $userModel;
    private $adminModel;
    
    public function __construct() {
        // Admin olmayan kullanıcıları engelle
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Sadece admin yetkisi olan kullanıcılara izin ver
        if(isset($_SESSION['user_data']) && $_SESSION['user_data']->username !== 'admin') {
            redirect('pages/index');
        }
        
        $this->userModel = $this->model('User');
        $this->adminModel = $this->model('Admin');
    }
    
    // Admin panel ana sayfa
    public function index() {
        // İstatistik verilerini al
        $userCount = $this->adminModel->getUserCount();
        $trackCount = $this->adminModel->getTrackCount();
        $playlistCount = $this->adminModel->getPlaylistCount();
        
        $data = [
            'title' => 'Yönetim Paneli',
            'userCount' => $userCount,
            'trackCount' => $trackCount,
            'playlistCount' => $playlistCount
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
    // Kullanıcı yönetimi
    public function users() {
        $users = $this->adminModel->getUsers();
        
        $data = [
            'title' => 'Kullanıcı Yönetimi',
            'users' => $users
        ];
        
        $this->view('admin/users', $data);
    }
    
    // Kullanıcı düzenleme
    public function editUser($id) {
        if($this->isPost()) {
            // Form verileri al
            $data = $this->getSanitizedPostData();
            
            // Kullanıcıyı güncelle
            if($this->adminModel->updateUser($id, $data)) {
                flash('admin_message', 'Kullanıcı başarıyla güncellendi');
                redirect('admin/users');
            } else {
                die('Bir hata oluştu');
            }
        } else {
            // Kullanıcı verisini al
            $user = $this->adminModel->getUserById($id);
            
            $data = [
                'title' => 'Kullanıcı Düzenle',
                'user' => $user
            ];
            
            $this->view('admin/edit_user', $data);
        }
    }
    
    // Kullanıcı silme
    public function deleteUser($id) {
        if($this->adminModel->deleteUser($id)) {
            flash('admin_message', 'Kullanıcı başarıyla silindi');
        } else {
            flash('admin_message', 'Kullanıcı silinemedi', 'alert alert-danger');
        }
        redirect('admin/users');
    }
    
    // Parça yönetimi
    public function tracks() {
        $tracks = $this->adminModel->getTracks();
        
        $data = [
            'title' => 'Parça Yönetimi',
            'tracks' => $tracks
        ];
        
        $this->view('admin/tracks', $data);
    }
    
    // Parça düzenleme
    public function editTrack($id) {
        if($this->isPost()) {
            // Form verileri al
            $data = $this->getSanitizedPostData();
            
            // Parçayı güncelle
            if($this->adminModel->updateTrack($id, $data)) {
                flash('admin_message', 'Parça başarıyla güncellendi');
                redirect('admin/tracks');
            } else {
                die('Bir hata oluştu');
            }
        } else {
            // Parça verisini al
            $track = $this->adminModel->getTrackById($id);
            
            $data = [
                'title' => 'Parça Düzenle',
                'track' => $track
            ];
            
            $this->view('admin/edit_track', $data);
        }
    }
    
    // Parça silme
    public function deleteTrack($id) {
        if($this->adminModel->deleteTrack($id)) {
            flash('admin_message', 'Parça başarıyla silindi');
        } else {
            flash('admin_message', 'Parça silinemedi', 'alert alert-danger');
        }
        redirect('admin/tracks');
    }
    
    // Çalma listesi yönetimi
    public function playlists() {
        $playlists = $this->adminModel->getPlaylists();
        
        $data = [
            'title' => 'Çalma Listesi Yönetimi',
            'playlists' => $playlists
        ];
        
        $this->view('admin/playlists', $data);
    }
    
    // Çalma listesi düzenleme
    public function editPlaylist($id) {
        if($this->isPost()) {
            // Form verileri al
            $data = $this->getSanitizedPostData();
            
            // Çalma listesini güncelle
            if($this->adminModel->updatePlaylist($id, $data)) {
                flash('admin_message', 'Çalma listesi başarıyla güncellendi');
                redirect('admin/playlists');
            } else {
                die('Bir hata oluştu');
            }
        } else {
            // Çalma listesi verisini al
            $playlist = $this->adminModel->getPlaylistById($id);
            $tracks = $this->adminModel->getTracks();
            $playlistTracks = $this->adminModel->getPlaylistTracks($id);
            
            $data = [
                'title' => 'Çalma Listesi Düzenle',
                'playlist' => $playlist,
                'tracks' => $tracks,
                'playlistTracks' => $playlistTracks
            ];
            
            $this->view('admin/edit_playlist', $data);
        }
    }
    
    // Çalma listesi silme
    public function deletePlaylist($id) {
        if($this->adminModel->deletePlaylist($id)) {
            flash('admin_message', 'Çalma listesi başarıyla silindi');
        } else {
            flash('admin_message', 'Çalma listesi silinemedi', 'alert alert-danger');
        }
        redirect('admin/playlists');
    }
    
    // Favoriler yönetimi
    public function favorites() {
        $favorites = $this->adminModel->getFavorites();
        
        $data = [
            'title' => 'Favoriler Yönetimi',
            'favorites' => $favorites
        ];
        
        $this->view('admin/favorites', $data);
    }
    
    // Favoriler silme
    public function deleteFavorite($id) {
        if($this->adminModel->deleteFavorite($id)) {
            flash('admin_message', 'Favori başarıyla silindi');
        } else {
            flash('admin_message', 'Favori silinemedi', 'alert alert-danger');
        }
        redirect('admin/favorites');
    }
}