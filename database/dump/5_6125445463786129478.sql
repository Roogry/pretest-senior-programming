/*
SQLyog Ultimate v12.4.1 (64 bit)
MySQL - 10.4.17-MariaDB : Database - db_latihan
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_latihan` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `db_latihan`;

/*Table structure for table `tb_barang` */

DROP TABLE IF EXISTS `tb_barang`;

CREATE TABLE `tb_barang` (
  `id_barang` char(5) NOT NULL,
  `nama_barang` varchar(30) DEFAULT NULL,
  `harga` bigint(20) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `id_jenis` char(3) DEFAULT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_barang` */

insert  into `tb_barang`(`id_barang`,`nama_barang`,`harga`,`stok`,`id_jenis`) values 
('BRG01','Susu',5000,0,'J02'),
('BRG02','Susu Ultra',5000,5,'J02'),
('BRG03','Susu Milo',6000,10,'J02'),
('BRG04','Teh Kotak',5000,7,'J02'),
('BRG05','Teh Sosro',6600,0,'J02'),
('BRG07','Teh Pucuk',7700,0,'J02'),
('BRG08','Indomie Kari Ayam',3000,5,'J01'),
('BRG09','Milo',1000,10,'J02'),
('BRG11','Indomie Soto',3000,5,'J01'),
('BRG12','Indomie Rendang',3000,10,'J01'),
('BRG13','Indomie Ayam Bawang',3000,10,'J01'),
('BRG14','Barang1',6000,10,'J02'),
('BRG15','Indomie Goreng',2000,8,'J01'),
('BRG16','Sarimi Soto',2000,6,'J01');

/*Table structure for table `tb_jenis` */

DROP TABLE IF EXISTS `tb_jenis`;

CREATE TABLE `tb_jenis` (
  `id_jenis` char(3) NOT NULL,
  `nama_jenis` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_jenis` */

insert  into `tb_jenis`(`id_jenis`,`nama_jenis`) values 
('J01','Makanan'),
('J02','Minuman');

/*Table structure for table `tb_pelanggan` */

DROP TABLE IF EXISTS `tb_pelanggan`;

CREATE TABLE `tb_pelanggan` (
  `id_pelanggan` char(3) NOT NULL,
  `nama_pelanggan` varchar(30) DEFAULT NULL,
  `alamat` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_pelanggan` */

insert  into `tb_pelanggan`(`id_pelanggan`,`nama_pelanggan`,`alamat`) values 
('P01','Putu Vida','Denpasar'),
('P02','Putu Toni','Denpasar'),
('P03','Made Rasni','Badung'),
('P04','Nyoman Tiga','Denpasar');

/*Table structure for table `tb_penjualan` */

DROP TABLE IF EXISTS `tb_penjualan`;

CREATE TABLE `tb_penjualan` (
  `id_penjualan` char(4) DEFAULT NULL,
  `id_pelanggan` char(3) DEFAULT NULL,
  `tgl_jual` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_penjualan` */

insert  into `tb_penjualan`(`id_penjualan`,`id_pelanggan`,`tgl_jual`) values 
('PJ01','P01','2021-03-03'),
('PJ02','P02','2021-03-04'),
('PJ03','P01','2021-03-10'),
('PJ04','P01','2022-02-16'),
('PJ05','P01','2022-02-17'),
('PJ06','P01','2022-03-16'),
('PJ07','P02','2022-03-17'),
('PJ08','P03','2022-03-17');

/*Table structure for table `tb_penjualan_detail` */

DROP TABLE IF EXISTS `tb_penjualan_detail`;

CREATE TABLE `tb_penjualan_detail` (
  `id_penjualan` char(4) DEFAULT NULL,
  `id_barang` char(5) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_jual` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_penjualan_detail` */

insert  into `tb_penjualan_detail`(`id_penjualan`,`id_barang`,`jumlah`,`harga_jual`) values 
('PJ01','BRG01',5,5000),
('PJ01','BRG02',5,5500),
('PJ02','BRG01',3,5000),
('PJ02','BRG03',10,6000),
('PJ03','BRG05',3,7000),
('PJ03','BRG08',5,3000),
('PJ03','BRG01',2,5000),
('PJ04','BRG02',5,5500),
('PJ04','BRG05',10,7000),
('PJ05','BRG04',10,5000),
('PJ05','BRG07',10,7000),
('PJ06','BRG16',2,2000),
('PJ06','BRG04',1,5000),
('PJ07','BRG04',2,5000),
('PJ07','BRG11',1,3000),
('PJ08','BRG11',2,3000),
('PJ08','BRG15',2,2000);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
