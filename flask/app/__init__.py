"""
Factory de la aplicación Flask.
Registra blueprints modulares y configura extensiones.
"""
from flask import Flask

from app.core.config import Config
from app.security.auth import login_manager


def create_app():
    app = Flask(__name__)
    app.config.from_object(Config)

    # Inicializar extensiones
    login_manager.init_app(app)

    # Registrar blueprints modulares
    from app.routers.auth_routes import auth
    from app.routers.main_routes import main
    from app.routers.inventario_routes import inventario
    from app.routers.pedidos_routes import pedidos_bp

    app.register_blueprint(auth)
    app.register_blueprint(main)
    app.register_blueprint(inventario)
    app.register_blueprint(pedidos_bp)

    return app