importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyD4XGTrPle1LBlcnaEJA8j5lXtu1aUC-X4",
    projectId: "easybuilding-d14f0",
    messagingSenderId: "541745851840",
    appId: "1:541745851840:web:547c94f535958229d8ec25"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function ({ data: { title, body, icon } }) {
    return self.registration.showNotification(title, { body, icon });
});
