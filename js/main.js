// Funciones de redirección para la navegación
function redirectToSignUp() {
    window.location.href = "auth/signup.html";
}

function redirectToLogIn() {
    window.location.href = "auth/login.html";
}

// Función para verificar si el usuario está autenticado
async function checkAuth() {
    const session = supabase.auth.session();
    if (session) {
        // Si el usuario está autenticado, redirigir al dashboard o página principal
        window.location.href = "dashboard.html";
    }
}

// Verificar autenticación cuando se carga la página
document.addEventListener('DOMContentLoaded', checkAuth);