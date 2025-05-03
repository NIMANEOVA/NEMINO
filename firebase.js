// firebase.js
// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyCatN7y_A5m43e51zVxMR4YgqhWEXG-a50",
  authDomain: "nemino-ir.firebaseapp.com",
  projectId: "nemino-ir",
  storageBucket: "nemino-ir.firebasestorage.app",
  messagingSenderId: "347542269134",
  appId: "1:347542269134:web:7bf1a032a81c81f680749f",
  measurementId: "G-QD1KPLYX45"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
