const CACHE_NAME = 'recipe-pwa-cache-v1';
const urlsToCache = [
    '/recipe_new/',
    '/recipe_new/index.php',
    '/recipe_new/add_recipe.php',
    '/recipe_new/edit_recipe.php',
    '/recipe_new/delete_recipe.php',
    '/recipe_new/fetch_recipes.php',
    '/recipe_new/add_recipe_action.php',
    '/recipe_new/edit_recipe_action.php',
    '/recipe_new/manifest.json',
    '/recipe_new/icons/icon-192x192.png',
    '/recipe_new/icons/icon-512x512.png'
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function(cache) {
            console.log('Opened cache');
            return cache.addAll(urlsToCache);
        }).catch(function(error) {
            console.error('Cache open failed:', error);
        })
    );
});

self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (!cacheWhitelist.includes(cacheName)) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

self.addEventListener('fetch', function(event) {
    if (event.request.method === 'GET') {
        event.respondWith(
            caches.match(event.request).then(function(response) {
                return response || fetch(event.request).then(function(networkResponse) {
                    return caches.open(CACHE_NAME).then(function(cache) {
                        cache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    });
                });
            }).catch(function() {
                return caches.match('/recipe_new/index.php');
            })
        );
    }
});

self.addEventListener('sync', function(event) {
    if (event.tag === 'sync-recipes') {
        event.waitUntil(syncRecipes());
    }
});

function syncRecipes() {
    const requests = JSON.parse(localStorage.getItem('sync-requests') || '[]');
    return Promise.all(requests.map(request => {
        return fetch(request.url, {
            method: request.method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(request.body)
        }).then(response => {
            if (response.ok) {
                localStorage.setItem('sync-requests', JSON.stringify(requests.filter(r => r.id !== request.id)));
            }
        });
    }));
}
