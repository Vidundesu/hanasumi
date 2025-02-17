-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: hanasumi
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `product_type` enum('flower','cake','custom_item') NOT NULL,
  `additional_info` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Blanche',6000.00,'Immerse yourself in a world of purity with our Blanche Bunch. Delicate white blossoms dance together, creating an enchanting arrangement that whispers of elegance and grace. Perfect for adding a touch of serenity to any space.','assets/images/flowers/prod1.jpg','flower','{\"color\": \"white\"}','2025-02-09 07:24:48'),(2,'Delicate Flora',6000.00,'Like a painter\'s masterpiece, the Delicate Flora bunch weaves a tapestry of colors and textures. Each bloom tells a story of nature\'s finest artistry, coming together in harmonious unity. Let these blooms paint your world with beauty.','assets/images/flowers/prod2.jpg','flower','{\"color\": \"mixed\"}','2025-02-09 07:24:48'),(3,'Pure White',5500.00,'Elegance in simplicity – our Pure White flowers symbolize peace and tranquility. Perfect for a graceful arrangement or as a centerpiece for any event.','assets/images/flowers/prod3.jpg','flower','{\"color\": \"white\"}','2025-02-09 07:24:48'),(4,'Ivory Serenity',6500.00,'Ivory Serenity brings calm to any environment with its soft, creamy blooms that gently evoke elegance and refinement. A timeless choice for any occasion.','assets/images/flowers/prod4.jpg','flower','{\"color\": \"ivory\"}','2025-02-09 07:24:48'),(5,'Pink Bloom',6000.00,'Soft and delicate, our Pink Bloom flowers convey warmth and love. A perfect way to celebrate a special occasion or express heartfelt emotions.','assets/images/flowers/prod5.jpg','flower','{\"color\": \"pink\"}','2025-02-09 07:24:48'),(6,'Black Chocolate & Peach Glazed',7200.00,'Experience the captivating dance between deep, dark chocolate and the gentle allure of peach glaze. Our Black Chocolate & Peach Glazed cake is a study in contrasts, where the bittersweet meets the delicate, creating a delightful harmony of taste and texture.','assets/images/cakes/cake5.jpg','cake','{\"flavor\": \"chocolate, peach\"}','2025-02-09 07:24:48'),(7,'Chocolate Swiss Roll with Buttercream & Ganache',6800.00,'Unwrap the allure of velvety chocolate in our Swiss Roll masterpiece. A tender chocolate sponge embraces luscious layers of buttercream and a rich ganache drizzle. An indulgence that unveils layers of comfort and decadence.','assets/images/cakes/cake2.jpg','cake','{\"flavor\": \"chocolate, buttercream\"}','2025-02-09 07:24:48'),(8,'Black Forest Cake',8000.00,'Indulge in the timeless appeal of our Black Forest cake, where layers of chocolate sponge are paired with luscious whipped cream, cherries, and a dusting of cocoa powder, offering a true taste of tradition.','assets/images/cakes/cake1.jpg','cake','{\"flavor\": \"chocolate, cherry, whipped cream\"}','2025-02-09 07:24:48'),(9,'Vanilla Dream Cake',7500.00,'For those who enjoy the simple pleasures in life, our Vanilla Dream Cake brings a delicate balance of light, airy vanilla sponge with rich, creamy frosting, a treat for the senses.','assets/images/cakes/cake3.jpg','cake','{\"flavor\": \"vanilla, cream\"}','2025-02-09 07:24:48'),(10,'Strawberry Delight Cake',7800.00,'Sweet, fruity, and oh-so-delicious, the Strawberry Delight cake features a moist vanilla base topped with a generous layer of fresh strawberries and creamy frosting, perfect for summer celebrations.','assets/images/cakes/cake4.jpg','cake','{\"flavor\": \"vanilla, strawberry\"}','2025-02-09 07:24:48'),(11,'Hanasumi Special Rose',1750.00,'Elegant and graceful white mixed with pinkish hues for a romantic touch.','assets/images/custom-items/r1.webp','custom_item','{\"type\": \"rose\", \"color\": \"white mixed pinkish\"}','2025-02-09 07:24:48'),(12,'Pure Pink Rose',1000.00,'Pure pink roses, ideal for expressing love and appreciation.','assets/images/custom-items/r2.webp','custom_item','{\"type\": \"rose\", \"color\": \"pure pink\"}','2025-02-09 07:24:48'),(13,'Red Velvet Cake',6500.00,'An indulgent classic, our Red Velvet Cake is a rich, moist cake with a smooth cream cheese frosting that will leave you wanting more.','assets/images/custom-items/cake1.jpg','custom_item','{\"type\": \"cake\", \"flavor\": \"red velvet\"}','2025-02-09 07:24:48'),(14,'Chocolate Box',2000.00,'A decadent assortment of the finest chocolates, perfect for gifting or indulging yourself.','assets/images/custom-items/box1.jpg','custom_item','{\"type\": \"chocolate\", \"flavor\": \"mixed\"}','2025-02-09 07:24:48'),(15,'Handmade Gift Box',1200.00,'A beautifully crafted gift box containing a variety of artisanal items, perfect for any occasion.','assets/images/custom-items/giftbox1.jpg','custom_item','{\"type\": \"gift\", \"contents\": \"assorted\"}','2025-02-09 07:24:48'),(16,'Hanasumi Special Rose',1750.00,'Beautiful white mixed pinkish roses','assets/images/custom-items/r1.webp','custom_item','{\"item_type\": \"roses\"}','2025-02-09 07:24:48'),(17,'Pure Pink Rose',1000.00,'Elegant pure pink roses','assets/images/custom-items/r2.webp','custom_item','{\"item_type\": \"roses\"}','2025-02-09 07:24:48'),(18,'Yellow Rose',750.00,'Bright pure yellow roses','assets/images/custom-items/r3.webp','custom_item','{\"item_type\": \"roses\"}','2025-02-09 07:24:48'),(19,'Light Pink Rose',750.00,'Delicate light pinkish roses','assets/images/custom-items/r4.jpg','custom_item','{\"item_type\": \"roses\"}','2025-02-09 07:24:48'),(20,'White Rose',850.00,'Classic pure white roses','assets/images/custom-items/r5.jpg','custom_item','{\"item_type\": \"roses\"}','2025-02-09 07:24:48'),(21,'Dailia',550.00,'Beautiful white dailia flowers','assets/images/custom-items/d1.webp','custom_item','{\"item_type\": \"dailia\"}','2025-02-09 07:24:48'),(22,'Dailia',900.00,'Vibrant deep yellow dailia flowers','assets/images/custom-items/d2.webp','custom_item','{\"item_type\": \"dailia\"}','2025-02-09 07:24:48'),(23,'Lilly',1200.00,'Elegant white mixed yellow lillies','assets/images/custom-items/f1.webp','custom_item','{\"item_type\": \"lilly\"}','2025-02-09 07:24:48'),(24,'Elephant Poo Paper',3000.00,'Unique elephant poo paper for wrapping','assets/images/custom-items/p2.webp','custom_item','{\"item_type\": \"wrap_paper\"}','2025-02-09 07:24:48'),(25,'Silky Paper',350.00,'Smooth and silky wrapping paper','assets/images/custom-items/p3.jpg','custom_item','{\"item_type\": \"wrap_paper\"}','2025-02-09 07:24:48'),(26,'Shredded Brown Paper',550.00,'Eco-friendly shredded brown paper','assets/images/custom-items/p1.webp','custom_item','{\"item_type\": \"wrap_paper\"}','2025-02-09 07:24:48');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-15 21:49:23
