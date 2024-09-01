## <p align="center" style="text-decoration: none !important;padding:0;margin:0;">Anemi Breytenbach <br> 231178 <br> DV 200 Term 3</p>

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
    * [Highlights](#highlights)
    * [Challenges](#challenges)
* [Future Implementation](#future-implementation)
* [Final Outcome](#final-outcome)
    * [Video Demonstration](#demonstration-video)
* [Conclusion](#conclusion)

## About the project:

### Mockup:
![MacBook_Dresser_Mockup_3_optimized_10](https://github.com/user-attachments/assets/69bee4e5-0e4c-462f-9f09-288306bf6622)


### Project description:
The goal of this project was to develop a web application that utilizes server-side technology to store user data and information. I chose to create a Q&A website tailored for history-related questions, allowing users to post and answer questions, as well as comment on historical topics. XAMPP was an ideal choice for this project, as it efficiently handles MySQL and PHP, enabling me to fully leverage its capabilities for managing my databases.

### Built with:
This project is built with MySQL, PHP and uses Xampp

## Getting Started:

### How to install:
* Download the files
* Place the historyscapers file inside your xampp htdox folder
* Place the historyscapers(MySQL) file inside your mysql data forlder, rename file to historyscapers
* Start XAMPP Control Panel
* Start Apache and MySQL
* Enjoy the web application

## Features and Functionality:
The web application consists of five main pages:

Sign Up and Log In Pages: These pages handle user registration and authentication.

Main Feed: This page displays all the questions posted by users, along with any attached images. Users can delete only the questions they have created. The page also features a search bar for finding relevant questions. By clicking on a question’s title, users are directed to the Answer page.

Answer Page: This page displays the selected question, its corresponding answers, and any comments. Users can post answers, comment on the question, and like both comments and answers.

Profile Page: Users can update their personal information, including their username, email, and password.

Create Question Page: Here, users can compose and post a new question by entering a title, the question body, and optionally adding relevant images.

Additionally, the header on each page includes a navigational link for logging out, which redirects users to the Log In screen.


## Concept Process:

### Ideation:
For the ideation phase, I envisioned a Q&A website specifically focused on historical questions, as history is a subject with a vast and dispersed range of information. I began by creating wireframes and refined them over time, ultimately arriving at the website's current design.

### Wireframes:
Log In, Sign Up, Main Feed, Post Question, View Question Answers

![Group 32](https://github.com/user-attachments/assets/6780b456-0121-44d0-86cc-326802b58bb4)


## Development Process

### Highlights
Working on this project was a deeply enriching experience for me. One of the most significant aspects was the hands-on practice with database design and management. Although I had some familiarity with these concepts before, this project really allowed me to solidify my understanding of relational integrity and data normalization, which were critical for the smooth operation and scalability of the system.

I also thoroughly enjoyed the challenge of working with PHP and MySQL. While I had never used these tools in previous projects, this time, I delved into their functionalities, especially in terms of user authentication and managing dynamic content. The complexities I encountered, such as implementing secure password hashing and managing user interactions, really helped me hone my problem-solving abilities.

Another highlight was the design process. Starting with wireframes and gradually refining them into the final user-friendly interface was both challenging and satisfying.

Overall, this project was a significant learning opportunity. It pushed me to expand my technical skills and apply them in a practical sense. I'm looking forward to taking these lessons forward and continuing to grow as a developer and use PHP and MySQL in upcoming projects.



### Challenges
During the development process, I encountered several challenges that required persistence and problem-solving. One of the most frustrating issues was dealing with constant file corruption errors. These errors would often occur unexpectedly, disrupting my workflow and forcing me to repair or restore files frequently. This not only slowed down the development process but also required me to implement more rigorous version control and backup practices to mitigate future risks.

Another significant challenge was getting the comments to properly connect with both the answers and the questions through a parent-child relationship in the database. Establishing these relationships was crucial for maintaining the integrity of the data and ensuring that comments were accurately linked to their corresponding answers and questions. It required careful planning of the database schema and a deep understanding of how to structure and query relational data effectively. Overcoming this challenge taught me valuable lessons in database management and reinforced the importance of thorough testing to ensure data consistency.

## Future Implementation
Looking back on this project, I see several opportunities for further enhancement and development on the code. I’m eager to enhance the user experience by making it more seamless and intuitive. One way to achieve this is by incorporating features commonly found in established Q&A platforms. For instance, adding profile pictures could personalize the experience, while implementing a more accurate like count would provide users with clearer feedback on the popularity of answers and comments. Expanding the functionality to include other features, such as badges or reputation scores, could further engage users and encourage more interaction on the platform.

By focusing on these improvements, I believe the platform can evolve into a more polished and feature-rich application, offering users a better and more engaging experience.

## Final Outcome

### Demonstration Video
https://drive.google.com/file/d/1w8nUzibAzd-msgpw5JEiZcX5wY6KR4Mz/view?usp=sharing


## Conclusion
This project has been a profoundly rewarding experience, providing me with invaluable opportunities to grow both technically and professionally. Throughout the development process, I've gained a deeper understanding of the complexities involved in creating a functional and user-friendly web application. From conceptualizing the initial idea to tackling real-world challenges like database management and code stabilization, I’ve learned a great deal about the intricacies of development.

One of the most significant takeaways from this project has been the enhancement of my programming skills, particularly in working with PHP, MySQL, and the broader aspects of web development. The challenges I faced, from file corruption errors to implementing complex parent-child relationships in the database, have pushed me to think critically and refine my approach to problem-solving. These experiences have not only strengthened my technical abilities but also underscored the importance of perseverance and attention to detail.

Moreover, this project has highlighted the ongoing nature of development. While I’m proud of the progress I’ve made, I’m also aware of the potential for further improvement and expansion. The future enhancements I’ve identified, such as streamlining the code and enriching the user experience, excite me, as they represent opportunities to continue building on the foundation I've established.

Overall, this project has been a highly educational and fulfilling journey. It has equipped me with practical skills and insights that will undoubtedly benefit me in my future endeavors. I am eager to continue refining this project and applying what I’ve learned as I advance in my career, confident that these experiences will serve as a strong foundation for whatever challenges lie ahead.
