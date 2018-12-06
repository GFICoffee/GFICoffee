/* tslint:disable:no-console */

import { register } from 'register-service-worker'

const applicationServerPublicKey = 'BI5qCj0NdNvjDcBYTIXiNccdcP74Egtb3WxuaXrHIVCLdM-MwqPkLplHozlMsM3ioINQ6S_HAexCM0UqKMvaYmg'

function urlB64ToUint8Array (base64String: string) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4)
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/')

  const rawData = window.atob(base64)
  const outputArray = new Uint8Array(rawData.length)

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i)
  }
  return outputArray
}

async function manageNotificationSubscription (registration: ServiceWorkerRegistration) {
  let subscription = await registration.pushManager.getSubscription()
  let isSubscribed: boolean = !(subscription === null)

  if (isSubscribed) {
    console.log('User IS subscribed.')
  } else {
    console.log('User is NOT subscribed.')
    const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey)
    try {
      subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
      })
      isSubscribed = true
      console.log('User just subscribed.')
    } catch (e) {
      console.error('Failed to subscribe the user: ', e)
    }
  }

  if (isSubscribed) {
    console.log(JSON.stringify(subscription))
  }
}

if (process.env.NODE_ENV === 'production') {
  register(`${process.env.BASE_URL}service-worker.js`, {
    ready () {
      console.log(
        'App is being served from cache by a service worker.\n' +
        'For more details, visit https://goo.gl/AFskqB'
      )
    },
    async registered (registration: ServiceWorkerRegistration) {
      console.log('Service worker has been registered.')
      await manageNotificationSubscription(registration)
    },
    cached () {
      console.log('Content has been cached for offline use.')
    },
    updated () {
      console.log('New content is available; please refresh.')
    },
    offline () {
      console.log('No internet connection found. App is running in offline mode.')
    },
    error (error) {
      console.error('Error during service worker registration:', error)
    }
  })
}
