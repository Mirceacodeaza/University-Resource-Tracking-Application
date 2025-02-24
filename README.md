This project is a web application designed to efficiently manage university infrastructure, including buildings, rooms, equipment, and staff. The system provides a centralized platform where users can authenticate through a secure login, gaining access to various features for data visualization, insertion, and filtering.

The application is built with phpMyAdmin and XAMPP for local server management, and the database itself is created in MySQL. The frontend and backend are developed in Visual Studio Code, providing a complete and dynamic environment for interacting with the database.

The database follows a well-structured relational model with tables capturing the relationships between key entities:

Buildings: Stores names, addresses, and number of floors.
Rooms: Includes room names, types (e.g., lab, lecture hall), capacity, and floor location, linked to buildings.
Equipment: Tracks equipment names, quantities, conditions, and purchase dates, linked to specific rooms.
Managers: Contains details of room managers (names, emails, phone numbers) associated with specific rooms.
Supervisors: Manages information about equipment supervisors and their specializations.
Room-Equipment Links: Implements many-to-many relationships between rooms and equipment.
The system enables users to perform various operations:

User Authentication: Access is restricted to registered users with valid credentials.
Data Viewing: Users can view organized tables for buildings, rooms, equipment, and personnel.
Data Insertion and Updates: Add, update, or delete records directly through the web interface.
Data Filtering: Use SQL queries to retrieve specific data, such as:
Rooms and their equipment.
Buildings with labs and assigned managers.
Functional or defective equipment.
Supervisors managing particular room assets.
To facilitate navigation, the web interface includes a menu that allows users to easily switch between sections. The combination of MySQL for robust data storage and PHP for server-side logic makes the system both powerful and user-friendly, offering a complete solution for university facility and equipment management.
