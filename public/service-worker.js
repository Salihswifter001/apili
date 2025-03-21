/**
 * Octaverum AI - Service Worker
 * Provides offline functionality and caching for the app
 */

const CACHE_NAME = 'octaverum-ai-cache-v1';
const CACHE_ASSETS = [
  '/',
  '/public/css/style.css',
  '/public/css/mobile.css',
  '/public/css/themes/dark.css',
  '/public/js/main.js',
  '/public/js/player.js',
  '/public/js/visualizer.js',
  '/public/img/default-album.png',
  '/public/img/icon-192.png',
  '/public/img/icon-512.png',
  '/users/login',
  '/users/register',
  '/pages/about',
  '/pages/pricing',
  '/music/demo'
];

// Install event - precaches assets
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Caching app assets');
        return cache.addAll(CACHE_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});

// Activate event - cleans up old caches
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            console.log('Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch event - serve cached content when offline
self.addEventListener('fetch', event => {
  // Skip cross-origin requests
  if (!event.request.url.startsWith(self.location.origin)) {
    return;
  }
  
  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }
  
  // Network first, fallback to cache strategy for API endpoints
  if (event.request.url.includes('/api/')) {
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Clone the response
          const responseClone = response.clone();
          
          // Open cache
          caches.open(CACHE_NAME)
            .then(cache => {
              // Add response to cache
              cache.put(event.request, responseClone);
            });
            
          return response;
        })
        .catch(() => caches.match(event.request))
    );
    return;
  }
  
  // Cache first, fallback to network strategy for assets
  event.respondWith(
    caches.match(event.request)
      .then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }
        
        return fetch(event.request)
          .then(response => {
            // Return the response if it's not valid for caching
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
            
            // Clone the response
            const responseClone = response.clone();
            
            // Open cache
            caches.open(CACHE_NAME)
              .then(cache => {
                // Add response to cache
                cache.put(event.request, responseClone);
              });
              
            return response;
          });
      })
      .catch(() => {
        // If both cache and network fail, return offline page
        if (event.request.url.indexOf('.html') > -1 || 
            event.request.url.endsWith('/')) {
          return caches.match('/');
        }
      })
  );
});

// Handle push notifications (for engagement like in Android apps)
self.addEventListener('push', event => {
  const data = event.data.json();
  
  const options = {
    body: data.body || 'New notification from Octaverum AI',
    icon: '/public/img/icon-192.png',
    badge: '/public/img/badge.png',
    vibrate: [100, 50, 100],
    data: {
      url: data.url || '/'
    }
  };
  
  event.waitUntil(
    self.registration.showNotification(
      data.title || 'Octaverum AI', 
      options
    )
  );
});

// Notification click handler
self.addEventListener('notificationclick', event => {
  event.notification.close();
  
  event.waitUntil(
    clients.openWindow(event.notification.data.url)
  );
});