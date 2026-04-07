from flask import Flask
from config import Config
from flask_login import LoginManager

login_manager = LoginManager()
login_manager.login_view = 'main.index'
login_manager.login_message = "Por favor inicie sesión para acceder a esta página."

def create_app():
    app = Flask(__name__)
    app.config.from_object(Config)

    login_manager.init_app(app)

    from .routes import main
    app.register_blueprint(main)

    return app