import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";

const firebaseConfig = {
  apiKey: "AIzaSyCatN7y_A5m43e51zVxMR4YgqhWEXG-a50",
  authDomain: "nemino-ir.firebaseapp.com",
  projectId: "nemino-ir",
  storageBucket: "nemino-ir.firebasestorage.app",
  messagingSenderId: "347542269134",
  appId: "1:347542269134:web:7bf1a032a81c81f680749f",
  measurementId: "G-QD1KPLYX45"
};

// مقداردهی Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

// مدیریت فرم ثبت‌نام
document.getElementById("signupForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    createUserWithEmailAndPassword(auth, email, password)
        .then((userCredential) => {
            document.getElementById("message").innerText = "ثبت‌نام با موفقیت انجام شد!";
            document.getElementById("message").style.color = "green";
        })
        .catch((error) => {
            let errorMessage = error.message;
            if (error.code === "auth/email-already-in-use") {
                errorMessage = "این ایمیل قبلاً ثبت شده است.";
            } else if (error.code === "auth/weak-password") {
                errorMessage = "رمز عبور باید حداقل ۶ کاراکتر باشد.";
            }
            document.getElementById("message").innerText = `خطا: ${errorMessage}`;
        });
});
