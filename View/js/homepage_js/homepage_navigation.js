// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Header buttons
    const loginBtnHeader = document.getElementById('login-btn-header');
    const registerBtnHeader = document.getElementById('register-btn-header');
    const aboutBtnHeader = document.getElementById('about-btn-header');

    // Hero buttons
    const registerBtnHero = document.getElementById('register-btn-hero');
    const loginBtnHero = document.getElementById('login-btn-hero');

    // Add event listeners for header buttons
    loginBtnHeader.addEventListener('click', () => {
        window.location.href = 'login.html';
    });

    registerBtnHeader.addEventListener('click', () => {
        window.location.href = '/View/templates/signUpForm.html';
    });

    aboutBtnHeader.addEventListener('click', () => {
        window.location.href = 'about.html';
    });

    // Add event listeners for hero buttons
    registerBtnHero.addEventListener('click', () => {
        window.location.href = '/View/templates/signUpForm.html';
    });

    loginBtnHero.addEventListener('click', () => {
        window.location.href = 'login.html';
    });
});
