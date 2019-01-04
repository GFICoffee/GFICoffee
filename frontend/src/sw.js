// workbox.precaching.precacheAndRoute(self.__precacheManifest || [])
// workbox.routing.registerNavigationRoute('/index.html')
//
// workbox.routing.registerRoute(
//   /.*coffee\/list/,
//   workbox.strategies.networkFirst({
//     cacheName: 'coffeelist-cache',
//     plugins: [
//       new workbox.expiration.Plugin({})
//     ],
//   })
// )
//
// self.addEventListener('push', function (event) {
//   const data = event.data ? event.data.text() : null
//   console.log('[Service Worker] Push Received.')
//   console.log(`[Service Worker] Push had this data: `, event)
//
//   const title = 'GFI Coffee'
//   const options = {
//     body: data,
//     icon: 'pwa/icon.png',
//     badge: 'pwa/badge.png'
//   }
//
//   const notificationPromise = self.registration.showNotification(title, options)
//   event.waitUntil(notificationPromise)
// })
