#!/bin/bash

# docker-deploy.sh - Docker deployment script for Construction Website

set -e

echo "🐳 Docker Deployment Script for Construction Website"
echo "=================================================="

# Function to print colored output
print_status() {
    echo -e "\n🔵 $1"
}

print_success() {
    echo -e "\n✅ $1"
}

print_error() {
    echo -e "\n❌ $1"
}

print_warning() {
    echo -e "\n⚠️  $1"
}

# Check if Docker is installed and running
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

if ! docker info &> /dev/null; then
    print_error "Docker is not running. Please start Docker first."
    exit 1
fi

# Check if Docker Compose is available
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    print_error "Docker Compose is not available. Please install Docker Compose."
    exit 1
fi

# Set Docker Compose command
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
else
    DOCKER_COMPOSE="docker compose"
fi

# Function to deploy for development
deploy_development() {
    print_status "Deploying for Development..."
    
    # Copy development environment file
    if [ ! -f .env ]; then
        cp .env.docker.dev .env
        print_status "Created .env file from .env.docker.dev"
    fi
    
    # Generate application key if not set
    if grep -q "APP_KEY=$" .env; then
        print_status "Generating application key..."
        $DOCKER_COMPOSE -f docker-compose.dev.yml run --rm app php artisan key:generate --no-interaction
    fi
    
    # Build and start development containers
    print_status "Building and starting development containers..."
    $DOCKER_COMPOSE -f docker-compose.dev.yml up -d --build
    
    # Wait for MySQL to be ready
    print_status "Waiting for MySQL to be ready..."
    sleep 10
    
    # Run migrations and seeders
    print_status "Running database migrations..."
    $DOCKER_COMPOSE -f docker-compose.dev.yml exec app php artisan migrate --force
    
    print_status "Running database seeders..."
    $DOCKER_COMPOSE -f docker-compose.dev.yml exec app php artisan db:seed --force || true
    
    # Create storage link
    print_status "Creating storage symlink..."
    $DOCKER_COMPOSE -f docker-compose.dev.yml exec app php artisan storage:link || true
    
    print_success "Development environment is ready!"
    echo ""
    echo "🌐 Application: http://localhost:8000"
    echo "📧 MailHog: http://localhost:8025"
    echo "🗄️ MySQL: localhost:3308 (user: construction_user, password: construction_password)"
    echo "📱 Vite Dev Server: http://localhost:5173"
    echo "🔴 Redis: localhost:6381"
    echo ""
    echo "To view logs: $DOCKER_COMPOSE -f docker-compose.dev.yml logs -f"
    echo "To stop: $DOCKER_COMPOSE -f docker-compose.dev.yml down"
}

# Function to deploy for production
deploy_production() {
    print_status "Deploying for Production..."
    
    # Copy production environment file
    if [ ! -f .env ]; then
        cp .env.docker .env
        print_status "Created .env file from .env.docker"
        print_warning "Please update database credentials and other settings in .env file"
    fi
    
    # Generate application key if not set
    if grep -q "APP_KEY=$" .env; then
        print_status "Generating application key..."
        $DOCKER_COMPOSE run --rm app php artisan key:generate --no-interaction
    fi
    
    # Build and start production containers
    print_status "Building and starting production containers..."
    $DOCKER_COMPOSE up -d --build
    
    # Wait for MySQL to be ready
    print_status "Waiting for MySQL to be ready..."
    sleep 15
    
    # Run migrations
    print_status "Running database migrations..."
    $DOCKER_COMPOSE exec app php artisan migrate --force
    
    # Create storage link
    print_status "Creating storage symlink..."
    $DOCKER_COMPOSE exec app php artisan storage:link || true
    
    # Cache configurations for production
    print_status "Caching configurations for production..."
    $DOCKER_COMPOSE exec app php artisan config:cache
    $DOCKER_COMPOSE exec app php artisan route:cache
    $DOCKER_COMPOSE exec app php artisan view:cache
    
    print_success "Production environment is ready!"
    echo ""
    echo "🌐 Application: http://localhost:8080"
    echo "🔄 Nginx Proxy: http://localhost:8081"
    echo "🗄️ MySQL: localhost:3307 (user: construction_user, password: construction_password)"
    echo "📱 Redis: localhost:6380"
    echo ""
    echo "To view logs: $DOCKER_COMPOSE logs -f"
    echo "To stop: $DOCKER_COMPOSE down"
}

# Function to stop all containers
stop_containers() {
    print_status "Stopping all containers..."
    
    if [ -f docker-compose.dev.yml ]; then
        $DOCKER_COMPOSE -f docker-compose.dev.yml down || true
    fi
    
    if [ -f docker-compose.yml ]; then
        $DOCKER_COMPOSE down || true
    fi
    
    print_success "All containers stopped!"
}

# Function to clean up Docker resources
cleanup() {
    print_status "Cleaning up Docker resources..."
    
    # Stop containers
    stop_containers
    
    # Remove images
    print_status "Removing Docker images..."
    docker images | grep construction | awk '{print $3}' | xargs docker rmi -f || true
    
    # Remove volumes (warning: this will delete data!)
    read -p "⚠️  Do you want to remove volumes (this will delete database data)? [y/N]: " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_status "Removing Docker volumes..."
        docker volume ls | grep construction | awk '{print $2}' | xargs docker volume rm || true
    fi
    
    print_success "Cleanup completed!"
}

# Function to show logs
show_logs() {
    if [ "$1" = "dev" ]; then
        $DOCKER_COMPOSE -f docker-compose.dev.yml logs -f
    else
        $DOCKER_COMPOSE logs -f
    fi
}

# Function to run artisan commands
run_artisan() {
    if [ "$1" = "dev" ]; then
        shift
        $DOCKER_COMPOSE -f docker-compose.dev.yml exec app php artisan "$@"
    else
        shift
        $DOCKER_COMPOSE exec app php artisan "$@"
    fi
}

# Function to enter container shell
shell() {
    if [ "$1" = "dev" ]; then
        $DOCKER_COMPOSE -f docker-compose.dev.yml exec app bash
    else
        $DOCKER_COMPOSE exec app bash
    fi
}

# Main script logic
case "$1" in
    "dev"|"development")
        deploy_development
        ;;
    "prod"|"production")
        deploy_production
        ;;
    "stop")
        stop_containers
        ;;
    "cleanup")
        cleanup
        ;;
    "logs")
        show_logs "$2"
        ;;
    "artisan")
        run_artisan "$@"
        ;;
    "shell")
        shell "$2"
        ;;
    "restart")
        stop_containers
        if [ "$2" = "dev" ]; then
            deploy_development
        else
            deploy_production
        fi
        ;;
    *)
        echo "🐳 Docker Deployment Script for Construction Website"
        echo ""
        echo "Usage: $0 {dev|prod|stop|cleanup|logs|artisan|shell|restart}"
        echo ""
        echo "Commands:"
        echo "  dev         - Deploy development environment"
        echo "  prod        - Deploy production environment"
        echo "  stop        - Stop all containers"
        echo "  cleanup     - Remove containers, images, and optionally volumes"
        echo "  logs [dev]  - Show container logs"
        echo "  artisan [dev] <command> - Run artisan command"
        echo "  shell [dev] - Enter container shell"
        echo "  restart [dev] - Restart containers"
        echo ""
        echo "Examples:"
        echo "  $0 dev                    # Start development environment"
        echo "  $0 prod                   # Start production environment"
        echo "  $0 logs dev               # Show development logs"
        echo "  $0 artisan dev migrate    # Run migration in dev"
        echo "  $0 shell                  # Enter production container"
        exit 1
        ;;
esac
