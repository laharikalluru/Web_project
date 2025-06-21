# 👶 Little Learners Emporium

A comprehensive e-commerce platform designed specifically for educational toys and learning materials for children. This web application provides a fun, interactive shopping experience with educational games, learning activities, and a curated selection of age-appropriate toys.

## 🌟 Features

### 🛍️ E-commerce Functionality
- **Product Catalog**: Browse educational toys by age group, category, and mood
- **Shopping Cart**: Add, remove, and manage items in cart
- **Wishlist**: Save favorite toys for later
- **User Authentication**: Secure login/register system
- **Order Management**: Complete checkout process with order confirmation

### 🎮 Interactive Learning Games
- **Memory Match Game**: Improve memory skills with card matching
- **Counting Fun**: Learn numbers through interactive gameplay
- **Educational Activities**: Age-appropriate learning exercises

### 🎯 Smart Features
- **Age-Based Recommendations**: Toys filtered by child's age group
- **Mood-Based Suggestions**: Find toys based on child's current mood
- **Gift Finder**: Help parents find the perfect educational gift
- **Product Reviews & Ratings**: Community-driven feedback system

### 📱 User Experience
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile
- **Modern UI**: Clean, child-friendly interface with engaging visuals
- **Accessibility**: Designed with children and parents in mind

## 🛠️ Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Custom CSS with Font Awesome icons
- **Server**: Apache/Nginx (XAMPP compatible)

## 📋 Prerequisites

Before running this project, make sure you have:

- **XAMPP** or similar local server environment
- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher
- **Web browser** (Chrome, Firefox, Safari, Edge)

## 🚀 Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/LittleLearnersEmporium_Final.git
cd LittleLearnersEmporium_Final
```

### Step 2: Set Up Local Server
1. Install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache and MySQL services
3. Place the project folder in `htdocs` directory

### Step 3: Database Setup
1. Open your web browser and navigate to `http://localhost/phpmyadmin`
2. Create a new database named `little_learners`
3. Import the database structure by running:
   ```
   http://localhost/LittleLearnersEmporium_Final/setup_database.php
   ```

### Step 4: Configure Database Connection
Update database credentials in `setup_database.php` if needed:
```php
$conn = new mysqli("localhost", "root", "your_password");
```

### Step 5: Access the Application
Open your browser and visit:
```
http://localhost/LittleLearnersEmporium_Final/
```

## 👤 Default Test Account

For testing purposes, you can use these credentials:
- **Email**: test@example.com
- **Password**: test123

## 📁 Project Structure

```
LittleLearnersEmporium_Final/
├── css/                    # Stylesheets
│   ├── style.css          # Main stylesheet
│   └── components/        # Component-specific styles
├── js/                    # JavaScript files
├── images/               # Product images and assets
├── includes/             # PHP includes and components
├── pages/                # Additional page templates
├── games/                # Interactive game files
├── activities/           # Learning activities
├── ajax/                 # AJAX handlers
├── database/             # Database-related files
├── sounds/               # Audio files for games
├── sql/                  # SQL scripts
├── index.php             # Homepage
├── catalog.php           # Product catalog
├── cart.php              # Shopping cart
├── checkout.php          # Checkout process
├── login.php             # User authentication
├── register.php          # User registration
├── wishlist.php          # Wishlist management
├── games.php             # Interactive games
├── learning-activities.php # Educational activities
├── setup_database.php    # Database setup script
└── database.sql          # Database schema
```

## 🎮 Available Games

### Memory Match
- Match pairs of cards to improve memory
- Multiple difficulty levels
- Timer and score tracking

### Counting Fun
- Interactive number learning
- Visual counting exercises
- Progressive difficulty

## 🛒 Shopping Features

### Product Categories
- **Grasping Toys**: For 0-2 age group
- **Educational Toys**: For 3-5 age group
- **Math Skills**: For 6-8 age group
- **Color ID**: Color recognition toys

### Age Groups
- **0-2**: Early development toys
- **3-5**: Preschool learning materials
- **6-8**: Elementary school resources

## 🔧 Customization

### Adding New Products
1. Add product details to the `products` table
2. Upload product images to `images/toys/` directory
3. Update catalog.php to display new products

### Modifying Games
- Game logic is in the `games/` directory
- JavaScript files handle game mechanics
- CSS styling in `css/components/games.css`

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Ensure MySQL service is running
- Check database credentials in setup files
- Verify database name is `little_learners`

**Images Not Loading**
- Check file permissions on images directory
- Verify image paths in database
- Ensure images are in correct format (JPG, PNG)

**Games Not Working**
- Check JavaScript console for errors
- Ensure all game assets are loaded
- Verify browser compatibility

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Development Team

- **Frontend Development**: HTML, CSS, JavaScript
- **Backend Development**: PHP, MySQL
- **UI/UX Design**: Child-friendly interface design
- **Game Development**: Interactive learning games

## 📞 Support

For support and questions:
- Create an issue in the GitHub repository
- Check the troubleshooting section above
- Review the code comments for implementation details

## 🔮 Future Enhancements

- [ ] Mobile app development
- [ ] Advanced recommendation system
- [ ] Parent dashboard with progress tracking
- [ ] Multi-language support
- [ ] Payment gateway integration
- [ ] Social sharing features
- [ ] Advanced analytics

---

**Made with ❤️ for little learners everywhere!**
