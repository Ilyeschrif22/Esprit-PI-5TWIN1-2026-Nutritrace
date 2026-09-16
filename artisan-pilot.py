import subprocess
import pyfiglet
from colorama import init, Fore, Style

def install_composer():
    print("Installing Composer...")

    result = subprocess.run(["cmd", "/c", "composer", "install"],text=True)

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)

import subprocess

def create_env():
    print("Copying .env.example to .env...")

    result = subprocess.run(
        ["cmd", "/c", "copy", ".env.example", ".env"],
        text=True,
        capture_output=True
    )
    print(result.stdout)
    if result.stderr:
        print("Error:", result.stderr)

    print("Generating application key...")
    result = subprocess.run(
        ["cmd", "/c", "php", "artisan", "key:generate"],
        text=True,
        capture_output=True
    )

    print(result.stdout)
    if result.stderr:
        print("Error:", result.stderr)


def run_application():
    print("Running the application...")

    result = subprocess.run(["cmd", "/c", "php", "artisan", "serve"],text=True)

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)

def database_migration():
    print("Migrating the database...")

    result = subprocess.run(["cmd", "/c", "php", "artisan", "migrate"],text=True)

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)

def clear_cache():
    print("Clearing the cache...")

    result = subprocess.run(
        ["cmd", "/c", "php", "artisan", "optimize:clear"],
        text=True,
        capture_output=True
    )

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)
    
    print("this might take a while, please wait...")
    # Refresh Composer autoload
    result = subprocess.run(
        ["cmd", "/c", "composer", "dump-autoload"],
        text=True,
        capture_output=True
    )

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)

def list_routes():
    print("Listing routes...")

    result = subprocess.run(["cmd", "/c", "php", "artisan", "route:list"],text=True)

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)

def create_model():
    model_name = input("Enter the name of the model: ")
    print(f"Creating model {model_name}...")

    result = subprocess.run(["cmd", "/c", "php", "artisan", "make:model", model_name],text=True)

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)

def create_controller():
    controller_name = input("Enter the name of the controller: ")
    print(f"Creating controller {controller_name}...")

    result = subprocess.run(["cmd", "/c", "php", "artisan", "make:controller", controller_name],text=True)

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)

def create_migration():
    migration_name = input("Enter the name of the migration: ")
    print(f"Creating migration {migration_name}...")

    result = subprocess.run(["cmd", "/c", "php", "artisan", "make:migration", migration_name],text=True)

    print(result.stdout)

    if result.stderr:
        print("Error:", result.stderr)

def menu():
    print("1. composer install")
    print("2. Configure the .env and application key") 
    print("3. migrate database")
    print("4. start application")
    print("5. clear cache")
    print("6. routes list")
    print("7. Create model")
    print("8. Create controller")
    print("9. Create migration")
    print("0. Exit")
    choice = input("Enter your choice: ")
    return choice

def response():
     while True:
        choice = menu()
        if choice == "1":
            install_composer()
        elif choice == "2":
            create_env()
        elif choice == "3":
            database_migration()
        elif choice == "4":
            run_application()
        elif choice == "5":
            clear_cache()
        elif choice == "6":
            list_routes()
        elif choice == "7":
            create_model()
        elif choice == "8":
            create_controller()
        elif choice == "9":
            create_migration()
        elif choice == "0":
            print("Exiting...")
            break
        else:
            print("Invalid choice. Please try again.")

def print_banner():
    ascii_art = pyfiglet.figlet_format("artisan-pilot", font="slant")
    print(Fore.CYAN + ascii_art)
    print(Fore.WHITE + "    Laravel artisan, on autopilot  " + Fore.YELLOW + "by ilyes" + Fore.WHITE + "    version " + Fore.GREEN + "1.0.0")
    print()

def main():
    print("Welcome to Artisan Pilot!")
    init(autoreset=True)  # makes colorama work properly on Windows terminal
    
    print_banner()
    response()


if __name__ == "__main__":
    main()