"""
Rutas de autenticación: login, logout, recuperar contraseña.
"""
from flask import Blueprint, render_template, request, redirect, url_for, flash
from flask_login import login_user, logout_user, login_required, current_user

from app.models.admin_session import AdminSession
from app.services.api_client import ApiClient

auth = Blueprint('auth', __name__)


@auth.route("/", methods=["GET", "POST"])
def index():
    if current_user.is_authenticated:
        return redirect(url_for('main.dashboard'))

    if request.method == "POST":
        email = request.form.get("email")
        password = request.form.get("password")

        try:
            response = ApiClient.login_admin(email, password)
            if response.status_code == 200:
                data = response.json()
                admin_user = AdminSession(data["admin_id"], data["nombre"], data["email"])
                login_user(admin_user)
                return redirect(url_for('main.dashboard'))
            elif response.status_code == 401:
                flash("Credenciales incorrectas. Por favor intentalo de nuevo.", "danger")
            else:
                flash("Error en el servidor de autenticación.", "danger")
        except Exception as e:
            flash("No se pudo contactar al servidor de la API.", "danger")
            print(e)

    return render_template("index.html")


@auth.route("/logout")
@login_required
def logout():
    logout_user()
    return redirect(url_for('auth.index'))


@auth.route("/recuperar")
def recuperar():
    if current_user.is_authenticated:
        return redirect(url_for('main.dashboard'))
    return render_template("recuperar_clave.html")
