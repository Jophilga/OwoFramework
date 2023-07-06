(() => {
    const SW_PREFIX = 'V1';
    const SW_PRELOAD = false;
    const SW_SCOPE = self.registration.scope;
    const SW_OFFLINE = `${SW_SCOPE}offline.html`;
    const SW_PRECACHE = [`${SW_SCOPE}?source=sw`, `${SW_SCOPE}manifest.json`];

    self.addEventListener('install', (event) => {
        console.log(`[SW] [${SW_PREFIX}] Installing ...`);
        event.waitUntil((async () => {
            try {
                caches.open(SW_PREFIX).then((cache) => {
                    cache.addAll([SW_SCOPE, SW_OFFLINE, ...SW_PRECACHE]);
                });
            }
            catch (e) {
                console.log(`[SW] [${SW_PREFIX}] Error: `, e);
            }

        })());
        self.skipWaiting();
    });

    self.addEventListener('activate', (event) => {
        console.log(`[SW] [${SW_PREFIX}] Activating ...`);
        event.waitUntil((async () => {
            try {
                if (SW_PRELOAD) {
                    if ('navigationPreload' in self.registration) {
                        await self.registration.navigationPreload.enable();
                    }
                }

                const keys = await caches.keys();
                if (keys) {
                    return await Promise.all(keys.map((key) => {
                        if (!key.includes(SW_PREFIX)) return caches.delete(key);
                        return;
                    }));
                }
            }
            catch (e) {
                console.log(`[SW] [${SW_PREFIX}] Error: `, e);
            }
        })());
        self.clients.claim();
    });

    self.addEventListener('fetch', (event) => {
        const request = event.request;
        console.log(`[SW] [${SW_PREFIX}] Fetching: ${request.url} [${request.method}]`);
        if (('only-if-cached' === request.cache) && ('same-origin' !== request.mode)) {
            return;
        }

        if (('navigate' === request.mode) || ('GET' === request.method)) {
            event.respondWith((async () => {
                try {
                    if (SW_PRELOAD) {
                        const responsePreload = await event.preloadResponse;
                        if (responsePreload) return responsePreload;
                    }

                    const responseCache = await caches.match(request);
                    if (responseCache) return responseCache;

                    const responseNetwork = await fetch(request);
                    if (responseNetwork) return responseNetwork;
                }
                catch (e) {
                    console.log(`[SW] [${SW_PREFIX}] Error: `, e);
                }

                const responseOffline = await caches.match(SW_OFFLINE);
                if (responseOffline) return responseOffline;
            })());

            event.waitUntil((async () => {
                try {
                    const responseNetwork = await fetch(request);
                    if (responseNetwork) {
                        const cache = await caches.open(SW_PREFIX);
                        if (cache) await cache.put(request, responseNetwork);
                    }
                }
                catch (e) {
                    console.log(`[SW] [${SW_PREFIX}] Error: `, e);
                }
            })());
        }
    });

    self.addEventListener('message', (event) => {
        console.log(`[SW] [${SW_PREFIX}] Messaging ...`);
        if ('skipWaiting' === event.data) {
            return self.skipWaiting();
        }
    });

    self.addEventListener('sync', (event) => {
        console.log(`[SW] [${SW_PREFIX}] Synchronizing ...`);
        event.waitUntil(handleBackgroundSync(event));
    });

    self.addEventListener('push', function(event) {
        console.log(`[SW] [${SW_PREFIX}] Pushing ...`);
        if (!self.notification || !('granted' === self.notification.permission)) {
            return;
        }

        let data = {};
        if (event.data) {
            data = event.data.json();
        }

        let title = data.title || 'Something Has Happened';
        let message = data.message || "Here's something you might want to check out";
        let notification = new Notification(title, {
            body: message,
            tag: 'simple-push-demo-notification',
            icon: icon ? icon : '/favicon.ico'
        });

        notification.addEventListener('click', () => {
            if (clients.openWindow) {
                return clients.openWindow('/');
            }
        });
    });

    self.addEventListener('notificationclick', (event) => {
        const notification = event.notification;
        console.log(`[SW] [${SW_PREFIX}] On notification click: ${notification.tag}`);
        notification.close();
        event.waitUntil(clients.matchAll({type: 'window'}).then((clientList) => {
            let i, l;
            for (i = 0, l = clientList.length; i < l; ++i) {
                let client = clientList[i];
                if (('/' === client.url) && ('focus' in client)) {
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow('/');
            }
        }));
    });

    /*
    ** Others functions
    */

    const handleBackgroundSync = (event) => {
        return new Promise((resolve, reject) => {
            /* your sync code here */
            resolve();
        });
    };
})();
