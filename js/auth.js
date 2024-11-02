async function signUpUser() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const message = document.getElementById('message');

    try {
        const { user, error } = await supabase.auth.signUp({
            email: email,
            password: password
        });

        if (error) {
            message.textContent = 'Error: ' + error.message;
        } else {
            message.textContent = 'User registered successfully. Check your email for confirmation!';
        }
    } catch (err) {
        message.textContent = 'Error: ' + err.message;
    }
}

async function logInUser() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const message = document.getElementById('message');

    try {
        const { user, error } = await supabase.auth.signIn({
            email: email,
            password: password
        });

        if (error) {
            message.textContent = 'Error: ' + error.message;
        } else {
            message.textContent = 'User logged in successfully!';
            // Redirigir después de un login exitoso
            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1500);
        }
    } catch (err) {
        message.textContent = 'Error: ' + err.message;
    }
}