## <p align="center" style="text-decoration: none !important;padding:0;margin:0;">Anemi Breytenbach <br> 231178 <br> DV 200 Term 3</p>

<p align="center">
<img src="historyscapers/images/logowhite.png" alt="Logo" width="160" height="140">
</p>

## Table of Contents

* [About the Project](#about-the-project)
  * [Mockup](#mockup)
  * [Project description](#project-description)
  * [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [How to install](#how-to-install)
* [Features and Functionality](#features-and-functionality)
* [Concept Process](#concept-process)
   * [Ideation](#ideation)
   * [Wireframes](#wireframes)
* [Development Process](#development-process)
    * [Implementation](#implementation)
    * [Highlights](#highlights)
    * [Challenges](#challenges)
* [Future Implementation](#future-implementation)
* [Final Outcome](#final-outcome)
    * [Video Demonstration](#demonstration-video)
* [Conclusion](#conclusion)
* [Author](#author)
* [License](#license)
* [Contact](#contact)
* [Acknowledgements](#acknowledgements)

## About the project:
![Screenshot (12182)](https://github.com/user-attachments/assets/40ef7da5-a123-4abd-b6f3-649818357118)

### Mockup:
![MacBook_Dresser_Mockup_3_optimized_10](https://github.com/user-attachments/assets/69bee4e5-0e4c-462f-9f09-288306bf6622)


### Project description:
The goal of this project was to develop a web application that utilizes server-side technology to store user data and information. I chose to create a Q&A website tailored for history-related questions, allowing users to post and answer questions, as well as comment on historical topics. XAMPP was an ideal choice for this project, as it efficiently handles MySQL and PHP, enabling me to fully leverage its capabilities for managing my databases.

### Built with:
- [PHP](https://www.php.net/)
- [MySQL](https://www.mysql.com/)
- [XAMPP](https://www.apachefriends.org/index.html)
- [JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

## Getting Started:
Follow the steps below to get a copy of the project running on your local machine.

### Prerequisites

- Download and install [XAMPP](https://www.apachefriends.org/index.html), which includes Apache, PHP, and MySQL.
- Make sure your system is running the latest version of PHP and MySQL.

### How to Install:
* Download the files
* Place the `historyscapers` folder inside your XAMPP `htdocs` folder
* Create a database called `historyscapers` and import the SQL file provided
* Start XAMPP Control Panel
* Start Apache and MySQL
* Enjoy the web application by navigating to `http://localhost/historyscapers`

## Features and Functionality:
The web application consists of five main pages:

- **Sign Up and Log In Pages**: Handle user registration and authentication.
- **Main Feed**: Displays all the questions posted by users along with attached images. Users can only delete or update their own questions. The page also features a search bar for finding relevant questions.
- **Answer Page**: Displays the selected question, answers, and comments. Users can post answers, comment, and like both answers and comments.
- **Profile Page**: Users can update their personal information, including username, email, and password.
- **Create Question Page**: Users can post new questions with a title, body, and optional images.

Each page includes a navigational link to log out and return to the Log In screen.

## Concept Process:

### Ideation:
For the ideation phase, I envisioned a Q&A website specifically focused on historical questions. Starting with wireframes, I refined the design to its current state.

### Wireframes:
Log In, Sign Up, Main Feed, Post Question, View Question Answers

![Group 32](https://github.com/user-attachments/assets/6780b456-0121-44d0-86cc-326802b58bb4)


## Development Process

### Implementation Process

- Implemented CRUD functionalities using PHP and MySQL.
- Styled the application using plain CSS and HTML.
- Integrated JavaScript for additional frontend functionality.

### Highlights:
One highlight was the hands-on practice with database design and management. This project solidified my understanding of relational integrity and data normalization. Working with PHP and MySQL, particularly in terms of user authentication and dynamic content, was another enjoyable challenge.

### Challenges:
One major challenge was file corruption errors, which required rigorous version control and backup practices. Another challenge was establishing parent-child relationships in the database for comments linked to both questions and answers. Overcoming these taught me valuable database management lessons.

## Future Implementation:
In the future, I plan to add features such as profile pictures, an improved like count, and possibly badges or reputation scores to further engage users.

## Final Outcome

### Demonstration Video
https://drive.google.com/file/d/1rvC4l12_sevLojgkqTrCnqQIp6AJeyN8/view?usp=sharing

## Roadmap
ER Diagram
![history drawio (1)](https://github.com/user-attachments/assets/5b0e7393-fa9b-4f1d-92e4-d694542e4a3d)

## Conclusion
This project has been an enriching experience, allowing me to grow both technically and professionally. I've gained a deeper understanding of database management and web development. Looking ahead, I see opportunities for further improvement and expansion, such as enhancing the user experience and adding new features. This journey has provided valuable lessons that will serve as a foundation for future endeavors.

## Author

- **Anemi Breytenbach** - [AnemiB](https://github.com/AnemiB)

## License

Distributed under the MIT License.

## Contact

- **Anemi Breytenbach** - [231178@virtualwindow.co.za] 
- **Project Link** - [ https://github.com/AnemiB/term3year](https://github.com/AnemiB/term3year)

## Acknowledgements

- [XAMPP](https://www.apachefriends.org/index.html)
- [PHP](https://www.php.net/)
- [MySQL](https://www.mysql.com/)
- [JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
