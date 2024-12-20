# AvocatConnect-Plateforme-de-R-servation-de-Consultations-Juridiques
Creation d'un site avec des fonctionnalités multi-rôles pour les utilisateurs et les avocats, une gestion de réservations, et un design moderne et responsive en PHP.




# Law Firm Consultation Management Website  

## Overview  
This project is a web application for a specialized law firm, providing clients and lawyers with an intuitive platform to manage consultation reservations.  

## Features  

### Clients  
- View profiles of lawyers (specialties, experience, etc.).  
- Register and log in.  
- Book consultations by selecting available time slots.  
- Manage reservations (view, modify, cancel).  

### Lawyers  
- Log in to a dedicated dashboard.  
- Manage reservations (approve or decline requests).  
- Update availability for consultations.  
- Edit professional profiles (photo, bio, specialties, contact info).  
- View detailed statistics:  
  - Pending requests.  
  - Approved requests for today and tomorrow.  
  - Details of the next client and reservation.  

### General Features  
- **Responsive Design**: Optimized for mobile, tablet, and desktop.  
- **Elegant Design**: Modern and professional UI/UX.  
- **JavaScript Functionalities**:  
  - Form validation using Regex.  
  - Dynamic modals for actions and alerts.  
  - SweetAlert integration.  
  - Real-time calendars for scheduling.  
- **Security**:  
  - Password hashing (bcrypt/Argon2).  
  - XSS protection (HTML sanitization).  
  - SQL injection prevention (prepared statements).  
  - CSRF protection (optional).  

### Bonus Features  
- Client reviews on lawyers.  
- PDF report generation for lawyers.  
- Email notifications (e.g., password reset, booking confirmations).  
- Custom 404 page.  

## Technologies Used  
- **Frontend**: HTML5, CSS3, JavaScript, tailwind.css 
- **Backend**: PHP  
- **Database**: MySQL  
 

## Installation  
1. Clone this repository:  
   ```bash  
   git clone https://github.com/Berrouk-Salma/AvocatConnect-Plateforme-de-R-servation-de-Consultations-Juridiques.git 
