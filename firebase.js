import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js';
    import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword, sendEmailVerification } from 'https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js';

    const firebaseConfig = {
      apiKey: "AIzaSyCatN7y_A5m43e51zVxMR4YgqhWEXG-a50",
      authDomain: "nemino-ir.firebaseapp.com",
      projectId: "nemino-ir",
      storageBucket: "nemino-ir.firebasestorage.app",
      messagingSenderId: "347542269134",
      appId: "1:347542269134:web:7bf1a032a81c81f680749f",
      measurementId: "G-QD1KPLYX45"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    auth.languageCode = 'fa';

    window.firebaseServices = {
      auth,
      createUserWithEmailAndPassword,
      signInWithEmailAndPassword,
      sendEmailVerification
    };

    console.log("Firebase initialized successfully");
