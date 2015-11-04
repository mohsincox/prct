-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2015 at 06:32 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ims`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `bankbook`(IN `pbid` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
 
SELECT bankbook.created_at,voucher.id as vid,voucher.vnno,voucher.type as type,voucher.amount,bankbook.id,suppliers.name as sname,customers.name as cname ,suppliers.id as sid,customers.id as cid,voucher.status,bankbook.dc 
from bankbook
LEFT OUTER JOIN suppliers  ON bankbook.sid=suppliers.id
LEFT OUTER JOIN customers  ON bankbook.cid=customers.id
LEFT OUTER JOIN voucher  ON bankbook.vid=voucher.id

where 
bankbook.baccid=pbid
AND voucher.vstatus
AND bankbook.created_at BETWEEN fromdate AND todate
ORDER BY bankbook.id ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cashanalyst`(IN `cid` INT)
    NO SQL
BEGIN
SELECT SUM(voucher.amount) as amount FROM voucher
WHERE voucher.cid=cid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `challanitem`(IN `sid` INT)
    NO SQL
BEGIN
SElECT DISTINCT items.id,items.name  
FROM factioyitems,items
WHERE factioyitems.itemsid=items.id
AND factioyitems.salesid=sid
ORDER BY factioyitems.itemsid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `contravoucher`(IN `pid` INT, IN `ptype` INT, IN `pstatus` INT)
    NO SQL
BEGIN
IF pstatus = 1 THEN 
SELECT * FROM
voucher 
WHERE id=pid;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `currentdate`()
    NO SQL
BEGIN
SELECT CURDATE() as curdate;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerbalance`(IN `pcid` INT, IN `pdate` DATE)
    NO SQL
BEGIN

DECLARE openb decimal(10,2);
DECLARE debit decimal(10,2);
DECLARE credit decimal(10,2);

SET openb = (select openbalance from customers where id=pcid);

SET debit = (select sum(amount) from customersledger where sv<>0 and cid=pcid and created_at<=pdate);

SET credit = (select sum(amount) from customersledger where rv<>0 and cid=pcid and created_at<=pdate);

select ((openb+debit)-credit) as openbalance ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customersledger`(IN `pcid` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

SELECT customersledger.cid,customersledger.amount,customersledger.created_at,customersledger.rv,voucher.vnno as rvoucher, 
customersledger.sv,sales.name as svoucher,customers.name as cname,voucher.id as vid,sales.id as sid,voucher.type as vtype,customers.code as ccode,voucher.vstatus,sales.status as sstatus
FROM customersledger
LEFT OUTER JOIN customers  ON customersledger.cid=customers.id
LEFT OUTER JOIN voucher  ON customersledger.rv=voucher.id
LEFT OUTER JOIN sales  ON customersledger.sv=sales.id
WHERE 
customersledger.created_at BETWEEN fromdate AND todate
AND customersledger.cid=pcid
ORDER BY customersledger.cid ASC;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customersledgerall`(IN `pcid` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
If pcid=0 THEN
SELECT customersledger.cid,customersledger.amount,customersledger.created_at,customersledger.rv,voucher.vnno as rvoucher, 
customersledger.sv,sales.name as svoucher,customers.name as cname,voucher.id as vid,sales.id as sid,voucher.type as vtype,customers.code as ccode,voucher.vstatus,sales.status as sstatus
FROM customersledger
LEFT OUTER JOIN customers  ON customersledger.cid=customers.id
LEFT OUTER JOIN voucher  ON customersledger.rv=voucher.id
LEFT OUTER JOIN sales  ON customersledger.sv=sales.id
WHERE 
customersledger.created_at BETWEEN fromdate AND todate

ORDER BY customersledger.cid ASC;

END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generalledger`(IN `pid` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

select pettycash.id,pettycash.created_at,pettycash.amount,pettycash.description,coa.name,increasetype.name as dc,coa.openbalance
from pettycash,coa,increasetype
WHERE 
pettycash.created_at BETWEEN fromdate AND todate
AND increasetype.id=coa.increasetypeid
AND coa.id=pettycash.particular
AND pettycash.particular=pid
ORDER BY pettycash.id ASC;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `itempurchase`()
    NO SQL
BEGIN

Select items.id,items.name 
from items,itemsgroup,itemssubgroup
where items.itemssubgroupid=itemssubgroup.id
and itemssubgroup.itemgroupid=itemsgroup.id
and itemsgroup.id=2;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ledgerentry`(IN `pid` INT)
    NO SQL
BEGIN
SELECT pettycash.id,pettycash.particular,pettycash.amount,pettycash.description,coa.name,coa.code,pettycash.created_at
FROM pettycash,coa
WHERE 
coa.id=pettycash.particular
AND
pettycash.id=pid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `purchasedetailsview`(IN `pid` INT)
    NO SQL
BEGIN
SELECT  items.name as iname,purchasedetails.quantity,purchasedetails.old_quantity,  purchasedetails.id, measurementunit.name as   mname,purchasedetails.rate,purchasedetails.old_rate,purchasedetails.amount,purchasedetails.old_amount
FROM items,purchasedetails,measurementunit,purchase
WHERE purchasedetails.purchaseid=purchase.id
AND purchasedetails.itemid=items.id
ANd purchasedetails.mesid=measurementunit.id
AND purchasedetails.purchaseid=pid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rptcash`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT voucher.created_at,voucher.vnno,voucher.id ,voucher.amount,voucher.type
FROM voucher
WHERE  
(voucher.type<>1 AND voucher.type<>3)

AND voucher.vstatus=1

AND voucher.created_at BETWEEN fromdate AND todate
UNION ALL
SELECT pettycash.created_at,pettycash.id as vnno ,pettycash.id,pettycash.amount,coa.increasetypeid as type
FROM pettycash,coa
WHERE
pettycash.particular=coa.id
AND
pettycash.created_at BETWEEN fromdate AND todate;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rptfactioyitems`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

select items.name as name,SUM(factioyitems.quantity) as cnt,measurementunit.name as mnane 
from factioyitems,items,measurementunit
where 
factioyitems.itemsid=items.id
AND
factioyitems.mesid=measurementunit.id
AND
factioyitems.created_at BETWEEN fromdate AND todate
GROUP BY factioyitems.itemsid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rptfactorypurchase`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

select items.name as name,SUM(purchasedetails.quantity) as fcnt,measurementunit.name as mname
from purchasedetails,items,measurementunit
where 
purchasedetails.itemid=items.id
AND
measurementunit.id=purchasedetails.mesid
AND
purchasedetails.created_at BETWEEN fromdate AND todate
GROUP BY purchasedetails.itemid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rptpurchase`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

select purchase.id,purchase.name,purchase.purchasedate,suppliers.name as suppliersname,purchase.suppliersbillno,purchase.suppliersbilldate,purchase.gross_total as amount
from purchase,suppliers
where
status=1
AND
purchase.suppliersid=suppliers.id
AND
purchase.created_at BETWEEN fromdate AND todate;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rptsales`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

select sales.id as id,sales.name as name,sales.salesdate as salesdate,customers.name as cname,customers.preaddress as address,customers.id as cid,sales.gamount,sales.previousdue,sales.presentbalance 
from 
sales,customers
where 
sales.customerid=customers.id
and
sales.status=1
and
sales.created_at BETWEEN fromdate AND todate
ORDER BY sales.id ASC
;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rptstockinout`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

select items.name as name,SUM(factioyitems.quantity) as fcnt
from purchasedetails,items,factioyitems
where 
purchasedetails.itemid=items.id
AND
factioyitems.itemsid=items.id
AND
purchasedetails.created_at BETWEEN fromdate AND todate
GROUP BY items.id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `salesanalyst`(IN `sid` INT)
    NO SQL
BEGIN
SELECT SUM(salesdetails.amount) as amount FROM salesdetails
WHERE salesdetails.salesid=sid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `salesdetailsview`(IN `pid` INT)
    NO SQL
BEGIN
SELECT  sales.id as sid,items.code as icode,items.name as iname,salesdetails.quantity,  measurementunit.name as   mname,salesdetails.rate,salesdetails.amount
FROM items,salesdetails,measurementunit,sales
WHERE salesdetails.salesid=sales.id
AND salesdetails.itemid=items.id
ANd salesdetails.mesid=measurementunit.id
AND salesdetails.salesid=pid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `salesreport`(IN `pvalue` INT)
    NO SQL
BEGIN
select created_at,sum(gamount) as total 
from sales
where status=1
and MONTH(created_at)=pvalue
group by created_at;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `salesreportp`(IN `pvalue` INT)
    NO SQL
BEGIN
select created_at,sum(gamount) as total 
from sales
where status=1
and MONTH(created_at)=pvalue
group by created_at;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `searchreturn`(IN `pslno` TEXT)
    NO SQL
BEGIN
SElECT factioyitems.slno, factioyitems.salesid, customers.name as cname
FROM factioyitems,sales,customers
WHERE factioyitems.salesid=sales.id
AND sales.customerid=customers.id
AND factioyitems.slno=pslno;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sub_ledger_voucher`(IN `pid` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

IF pid=2 THEN
SELECT voucher.id,voucher.type,voucher.vnno,voucher.vdate,voucher.amount,customers.name,voucher.created_at,increasetype.name as dc,coa.openbalance
FROM voucher,customers,coa,increasetype
WHERE  voucher.created_at BETWEEN fromdate AND todate
AND voucher.vstatus=1
AND voucher.type=4
AND voucher.cid=customers.id
AND coa.id=2
AND increasetype.id=coa.increasetypeid
ORDER BY voucher.id ASC;

ELSEIF pid=3 THEN
SELECT voucher.id,voucher.type,voucher.vnno,voucher.vdate,voucher.amount,suppliers.name,voucher.created_at,increasetype.name as dc,coa.openbalance
FROM voucher,suppliers,coa,increasetype
WHERE  voucher.created_at BETWEEN fromdate AND todate
AND voucher.vstatus=1
AND voucher.type=2
AND voucher.sid=suppliers.id
AND coa.id=3
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=4 THEN
SELECT voucher.id,voucher.type,voucher.vnno,voucher.vdate,voucher.amount,customers.name,voucher.created_at,increasetype.name as dc,coa.openbalance
FROM voucher,customers,coa,increasetype
WHERE  voucher.created_at BETWEEN fromdate AND todate
AND voucher.vstatus=1
AND voucher.type=6
AND voucher.cid=customers.id
AND coa.id=4
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=5 THEN
SELECT voucher.id,voucher.type,voucher.vnno,voucher.vdate,voucher.amount,customers.name,voucher.created_at,increasetype.name as dc,coa.openbalance
FROM voucher,customers,coa,increasetype
WHERE  voucher.created_at BETWEEN fromdate AND todate
AND voucher.vstatus=1
AND voucher.type=7
AND voucher.cid=customers.id
AND coa.id=5
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=6 THEN
SELECT voucher.id,voucher.type,voucher.vnno,voucher.vdate,voucher.amount,customers.name,voucher.created_at,increasetype.name as dc,coa.openbalance
FROM voucher,customers,coa,increasetype
WHERE  voucher.created_at BETWEEN fromdate AND todate
AND voucher.vstatus=1
AND voucher.type=8
AND voucher.cid=customers.id
AND coa.id=6
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=7 THEN
SELECT voucher.id,voucher.type,voucher.vnno,voucher.vdate,voucher.amount,customers.name,voucher.created_at,increasetype.name as dc,coa.openbalance
FROM voucher,customers,coa,increasetype
WHERE  voucher.created_at BETWEEN fromdate AND todate
AND voucher.vstatus=1
AND voucher.type=9
AND voucher.cid=customers.id
AND coa.id=7
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=8 THEN
SELECT voucher.id,voucher.type,voucher.vnno,voucher.vdate,voucher.amount,customers.name,voucher.created_at,increasetype.name as dc,coa.openbalance
FROM voucher,customers,coa,increasetype
WHERE  voucher.created_at BETWEEN fromdate AND todate
AND voucher.vstatus=1
AND voucher.type=3
AND voucher.cid=customers.id
AND coa.id=8
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=9 THEN
SELECT voucher.id,voucher.type,voucher.vnno,voucher.vdate,voucher.amount,suppliers.name,voucher.created_at,increasetype.name as dc,coa.openbalance
FROM voucher,suppliers,coa,increasetype
WHERE  voucher.created_at BETWEEN fromdate AND todate
AND voucher.vstatus=1
AND voucher.type=1
AND voucher.sid=suppliers.id
AND coa.id=9
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=11 THEN
SELECT sales.id,sales.name as salesname,sales.salesdate,sales.gamount,customers.name as cname,increasetype.name as dc,coa.openbalance
FROM sales,customers,coa,increasetype
WHERE  sales.created_at BETWEEN fromdate AND todate
AND sales.status=1
AND sales.customerid=customers.id
AND coa.id=11
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=12 THEN
SELECT purchase.id,purchase.name as purchasename, purchase.purchasedate, purchase.gross_total,suppliers.name as sname,increasetype.name as dc,coa.openbalance
FROM purchase,suppliers,coa,increasetype
WHERE  purchase.created_at BETWEEN fromdate AND todate
AND purchase.status=1
AND purchase.suppliersid=suppliers.id
AND coa.id=12
AND increasetype.id=coa.increasetypeid;

ELSEIF pid=13 THEN
SELECT employeeinfo.name as employeename, employeesal.amount, particulars.name as particularsname, employeesal.vdate, employeesal.description,increasetype.name as dc,coa.openbalance
FROM employeesal,particulars,employeeinfo,coa,increasetype
WHERE  employeesal.created_at BETWEEN fromdate AND todate
AND employeesal.eid=employeeinfo.id
AND employeesal.pid=particulars.id
AND coa.id=13
AND increasetype.id=coa.increasetypeid;
END IF;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `suppliersledger`(IN `psid` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT suppliersledger.sid,suppliersledger.amount,suppliersledger.created_at,suppliersledger.pav,suppliersledger.puv,voucher.vnno as pavoucher,purchase.name as puvoucher,suppliers.name as sname,voucher.id as vid,purchase.id as puid, voucher.type as vtype, suppliers.code as scode,voucher.vstatus, purchase.status as pustatus
FROM suppliersledger
LEFT OUTER JOIN suppliers  ON suppliersledger.sid=suppliers.id
LEFT OUTER JOIN voucher  ON suppliersledger.pav=voucher.id
LEFT OUTER JOIN purchase  ON suppliersledger.puv=purchase.id
WHERE suppliersledger.created_at BETWEEN fromdate AND todate
AND suppliersledger.sid=psid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `suppliersledgerall`(IN `psid` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
If psid=0 THEN
SELECT suppliersledger.sid,suppliersledger.amount,suppliersledger.created_at,suppliersledger.pav,suppliersledger.puv,voucher.vnno as pavoucher,purchase.name as puvoucher,suppliers.name as sname,voucher.id as vid,purchase.id as puid, voucher.type as vtype, suppliers.code as scode,voucher.vstatus, purchase.status as pustatus
FROM suppliersledger
LEFT OUTER JOIN suppliers  ON suppliersledger.sid=suppliers.id
LEFT OUTER JOIN voucher  ON suppliersledger.pav=voucher.id
LEFT OUTER JOIN purchase  ON suppliersledger.puv=purchase.id
WHERE suppliersledger.created_at BETWEEN fromdate AND todate;
END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaybankcollection`()
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at=CURDATE()
AND vstatus=1
AND type=3;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaybkashcollection`()
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at=CURDATE()
AND vstatus=1
AND type=6;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaycash`()
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at=CURDATE()
AND
type IN (3,4,6,7,8,9)
AND vstatus=1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaycashcollection`()
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at=CURDATE()
AND vstatus=1
AND type=4;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaycollection`()
    NO SQL
BEGIN
SELECT voucher.id,customers.name,customers.preaddress,voucher.amount,voucher.vnno,voucher.type,voucher.vstatus
FROM voucher,customers
WHERE
voucher.type<>1
and voucher.type<>2
and voucher.type<>5
and voucher.vstatus=1
and voucher.cid=customers.id
and voucher.created_at=CURDATE()
order by voucher.cid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaycontracollection`()
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at=CURDATE()
AND type=5
AND vstatus=1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaykcscollection`()
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at=CURDATE()
AND vstatus=1
AND type=8;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaymbankcollection`()
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at=CURDATE()
AND vstatus=1
AND type=9;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaypettycash`(IN `pid` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN

IF pid=2 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND type=4;

ELSEIF pid=3 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND type=2;

ELSEIF pid=4 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND type=6;

ELSEIF pid=5 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND type=7;

ELSEIF pid=6 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND type=8;

ELSEIF pid=7 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND type=9;

ELSEIF pid=8 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND type=3;

ELSEIF pid=9 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND type=1;

ELSEIF pid=11 THEN
SELECT SUM(gamount) as cash
FROM sales
WHERE  created_at BETWEEN fromdate AND todate
AND status=1;

ELSEIF pid=12 THEN
SELECT SUM(gross_total) as cash
FROM purchase
WHERE  created_at BETWEEN fromdate AND todate
AND status=1;

ELSEIF pid=13 THEN
SELECT SUM(amount) as cash
FROM employeesal
WHERE  created_at BETWEEN fromdate AND todate;

ELSEIF pid=14 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  vstatus=1
AND created_at <= fromdate ;

ELSEIF pid=15 THEN
SELECT SUM(amount) as cash
FROM voucher
WHERE  vstatus=1
AND created_at < todate ;

ELSE
SELECT SUM(pettycash.amount) as cash
FROM pettycash,coa
WHERE  
pettycash.created_at BETWEEN fromdate AND todate
AND pettycash.particular=coa.id
AND pettycash.particular=pid;
END IF;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaysales`()
    NO SQL
BEGIN
SELECT SUM(gamount) as sales
FROM sales
WHERE
status=1
and created_at=CURDATE();

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todaysapcollection`()
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at=CURDATE()
AND vstatus=1
AND type=7;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalcash`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at BETWEEN fromdate AND todate
AND vstatus=1
AND (type<>1 AND type<>2);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalcashcollection`(IN `todate` DATE)
    NO SQL
BEGIN
DECLARE vcash decimal(10,2);
DECLARE vpayment decimal(10,2);
DECLARE pcash decimal(10,2);
DECLARE ppayment decimal(10,2);
DECLARE ccash decimal(10,2);
DECLARE cpayment decimal(10,2);

SET vcash=(SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at<todate
AND
type IN (3,4,6,7,8,9)
AND vstatus=1);

SET vpayment=(SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at<todate
AND
type IN (2)
AND vstatus=1);




SET ccash=(SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at<todate
AND
type=5
AND status=1              
AND vstatus=1);




SET cpayment=(SELECT SUM(amount) as cash
FROM voucher
WHERE  created_at<todate
AND
type=5
AND status=2              
AND vstatus=1);



SET pcash=(SELECT SUM(pettycash.amount) as cash
FROM pettycash,coa
WHERE pettycash.created_at<todate
AND coa.increasetypeid=1
AND coa.id=pettycash.particular
);



SET ppayment=(SELECT SUM(pettycash.amount) as cash
FROM pettycash,coa
WHERE pettycash.created_at<todate
AND coa.increasetypeid=2
AND coa.id=pettycash.particular
);

IF(vcash IS NULL) THEN
	SET vcash = 0.00;
END IF;
If(vpayment IS NULL) THEN
	SET vpayment=0.00;
END IF;
If(ccash IS NULL) THEN
	SET ccash=0.00;
END IF;
If(cpayment IS NULL) THEN
	SET cpayment=0.00;
END IF;
If(pcash IS NULL) THEN
	SET pcash=0.00;
END IF;
If(ppayment IS NULL) THEN
	SET ppayment=0.00;
END IF;

select vcash-vpayment+ccash-cpayment+pcash-ppayment as cash;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalcashview`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT voucher.id,voucher.type,voucher.vnno, voucher.vdate, voucher.amount,customers.name
FROM voucher,customers
WHERE voucher.cid=customers.id
AND voucher.created_at BETWEEN fromdate AND todate;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalcredit`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT SUM(pettycash.amount) as cash
FROM pettycash,coa,coatype
WHERE  
pettycash.created_at BETWEEN fromdate AND todate
AND pettycash.particular=coa.id
AND coa.coatypeid=coatype.id
AND coa.increasetypeid=2;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totaldebit`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT SUM(pettycash.amount) as cash
FROM pettycash,coa,coatype
WHERE  
pettycash.created_at BETWEEN fromdate AND todate
AND pettycash.particular=coa.id
AND coa.coatypeid=coatype.id
AND coa.increasetypeid=1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalpettycash`(IN `id` INT, IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT SUM(pettycash.amount) as cash
FROM pettycash,coa,coatype
WHERE  
pettycash.created_at BETWEEN fromdate AND todate
AND pettycash.particular=coa.id
AND coa.coatypeid=coatype.id
AND coatype.id=id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalsales`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT SUM(gamount) as sales
FROM sales
WHERE created_at BETWEEN fromdate AND todate
AND status=1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `totalsalesview`(IN `fromdate` DATE, IN `todate` DATE)
    NO SQL
BEGIN
SELECT  sales.id,sales.name, sales.salesdate, sales.created_at, customers.name as cname
FROM sales, customers
WHERE sales.customerid=customers.id
AND sales.created_at BETWEEN fromdate AND todate;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `viewpurchase`(IN `pid` INT)
    NO SQL
BEGIN
SELECT  purchase.id, purchase.challanno as challanno,purchase.name as pname,purchase.purchasedate,  suppliers.name as   sname,suppliers.preaddress as   address,purchase.suppliersbillno,purchase.suppliersbilldate,
purchase.sub_total,purchase.discount,purchase.gross_total,
purchase.others_exp,purchase.status,
purchase.old_sub_total,purchase.old_discount,purchase.old_gross_total,
purchase.old_others_exp
FROM purchase,suppliers
WHERE purchase.suppliersid=suppliers.id AND
purchase.id=pid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `viewsales`(IN `pid` INT)
    NO SQL
BEGIN
SELECT  sales.id,sales.name as sname,sales.salesdate,sales.discount,sales.status,customers.name as   cname,customers.phone,customers.preaddress,customers.openbalance,sales.previousdue,sales.presentbalance,sales.gamount
FROM sales,customers
WHERE sales.customerid=customers.id AND
sales.id=pid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `voucher`(IN `pid` INT(10), IN `ptype` INT(10))
    NO SQL
BEGIN
IF ptype = 1 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,bankinfo.name as bname,bankaccount.branchname as branchname,
bankaccount.name as accno,voucherbankpayment.checkno,
voucher.vdate,bankaccount.id as baid,suppliers.id as sid,bankaccount.code as bacode,suppliers.code as scode
FROM 
voucher,voucherbankpayment,bankaccount,bankinfo,suppliers
where voucher.id=voucherbankpayment.vid 
and   bankaccount.id=voucherbankpayment.baccid
and   bankinfo.id=bankaccount.bankid
and   suppliers.id=voucher.sid 
and voucher.id=pid;
ELSEIF ptype = 2 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,suppliers.id as sid,suppliers.code as scode,voucher.vdate 
FROM voucher,voucherpayment,suppliers
where voucher.id=voucherpayment.vid 
and suppliers.id=voucher.sid
and   voucher.id=pid;
ELSEIF ptype = 3 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,bankinfo.name as bname,bankaccount.branchname as branchname,
bankaccount.name as accno,voucherbankreceive.checkno,
voucher.vdate,bankaccount.id as baid,customers.id as cid,bankaccount.code as bacode,customers.code as ccode 
FROM voucher,voucherbankreceive,bankaccount,bankinfo,customers
where voucher.id=voucherbankreceive.vid 
and   bankaccount.id=voucherbankreceive.baccid
and   bankinfo.id=bankaccount.bankid
and   customers.id=voucher.cid 
and voucher.id=pid;
ELSEIF ptype = 4 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,customers.id as cid,customers.code as ccode,voucher.vdate  
FROM voucher,voucherreceive,customers where 
voucher.id=voucherreceive.vid 
and  customers.id=voucher.cid
and voucher.id=pid;
ELSEIF ptype = 5 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,voucher.vdate,bankaccount.id as baid,bankaccount.code as bacode,
bankinfo.name as bname,bankaccount.name as baname,bankaccount.branchname as branchname,vouchercontra.checkno
FROM voucher,vouchercontra,bankaccount,bankinfo
where  bankaccount.id=vouchercontra.baccid
and   bankinfo.id=bankaccount.bankid
and voucher.id=vouchercontra.vid and voucher.id=pid;
ELSEIF ptype = 6 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,customers.id as cid,customers.code as ccode,voucher.vdate  
FROM voucher,voucherbkash,customers where 
voucher.id=voucherbkash.vid 
and  customers.id=voucher.cid
and voucher.id=pid;

ELSEIF ptype = 7 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,customers.id as cid,customers.code as ccode,voucher.vdate  
FROM voucher,vouchersap,customers where 
voucher.id=vouchersap.vid 
and  customers.id=voucher.cid
and voucher.id=pid;

ELSEIF ptype = 8 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,customers.id as cid,customers.code as ccode,voucher.vdate  
FROM voucher,voucherkcs,customers where 
voucher.id=voucherkcs.vid 
and  customers.id=voucher.cid
and voucher.id=pid;

ELSEIF ptype = 9 THEN 
SELECT voucher.vnno as vrno,voucher.type as mvno,voucher.amount,customers.id as cid,customers.code as ccode,voucher.vdate  
FROM voucher,vouchermbank,customers where 
voucher.id=vouchermbank.vid 
and  customers.id=voucher.cid
and voucher.id=pid;


END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bankaccount`
--

CREATE TABLE IF NOT EXISTS `bankaccount` (
`id` int(11) NOT NULL,
  `code` text COLLATE utf32_bin NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `opendate` date NOT NULL,
  `exdate` date NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `openbalance` decimal(10,2) NOT NULL,
  `bankid` int(11) NOT NULL,
  `branchname` text COLLATE utf32_bin NOT NULL,
  `accotitle` text COLLATE utf32_bin NOT NULL,
  `coastatus` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `bankaccount`
--

INSERT INTO `bankaccount` (`id`, `code`, `name`, `opendate`, `exdate`, `rate`, `openbalance`, `bankid`, `branchname`, `accotitle`, `coastatus`, `userid`, `created_at`, `updated_at`) VALUES
(2, 'C: 1002', '20501970100380400', '2015-08-26', '2015-08-26', '0.00', '500000.00', 3, 'Islampur', 'Bimal Chandra Mondol', 1, 1, '2015-09-15 06:11:53', '2015-09-15 00:11:53'),
(4, '253', '005533019234', '2013-08-04', '2025-08-27', '0.00', '755078.00', 1, 'Sadarghat', 'Agrani Traders', 1, 1, '2015-09-15 06:13:29', '2015-09-15 00:13:29'),
(9, '1212', '1212', '0000-00-00', '0000-00-00', '11.00', '111.00', 5, '11', '', 1, 1, '2015-09-15 06:11:32', '2015-09-15 00:11:32'),
(10, '1212', '1212', '0000-00-00', '0000-00-00', '11.00', '111.00', 5, '11', '', 1, 1, '2015-09-15 06:11:32', '2015-09-15 00:11:32'),
(11, '1212', '1212', '2015-08-26', '2015-08-26', '11.00', '111.00', 3, '12', '', 1, 1, '2015-09-15 06:11:32', '2015-09-15 00:11:32'),
(13, '800', '118-2045484', '2015-08-01', '2015-11-26', '10.00', '500000.00', 6, 'Gulsan', '', 1, 1, '2015-09-15 06:12:10', '2015-09-15 00:12:10'),
(14, '333', '333', '2015-08-30', '2015-08-30', '3333.00', '3333.00', 0, '3333', '', 0, 1, '2015-08-29 22:21:28', '2015-08-29 22:21:28'),
(16, 'B-27698', '118-2045484', '2010-08-09', '2030-08-30', '10.00', '8000000.00', 3, 'Gulsan', '', 1, 1, '2015-09-15 06:13:58', '2015-09-15 00:13:58'),
(17, 'B-23401', '1115-021541', '2010-08-03', '2015-08-30', '10.00', '150000.00', 9, 'Gulsan', '', 1, 1, '2015-09-15 06:12:28', '2015-09-15 00:12:28'),
(24, 'B-21256', 'aaa', '2015-09-08', '2015-09-08', '7.00', '8.00', 5, 'gulsan', 'Agrani Traders', 1, 1, '2015-09-15 06:14:12', '2015-09-15 00:14:12'),
(25, 'B-3892', 'rrr', '2015-09-08', '2015-09-08', '4.00', '5.00', 1, 'rrr', 'Agrani Traders', 1, 1, '2015-09-15 06:12:45', '2015-09-15 00:12:45'),
(26, 'B-30360', 'aa', '2015-09-08', '2015-09-08', '5.00', '5.00', 1, 'aa', 'Agrani Traders', 1, 1, '2015-09-15 06:13:16', '2015-09-15 00:13:16'),
(27, 'B-23335', '22111050000083', '2015-09-14', '2015-09-21', '10.00', '20000.00', 12, 'Patuatuli', 'J. Co. Distribution', 1, 1, '2015-09-01 09:33:26', '2015-09-01 03:33:26'),
(28, 'B-19328', '1401614747001', '2015-09-21', '2015-09-07', '10.00', '200000.00', 13, 'Patuatuli', 'j. Co. Battery Engineering Works', 1, 1, '2015-09-21 06:34:32', '2015-09-21 00:34:32'),
(29, 'B-21944', '103.107.7841', '2015-10-27', '1970-01-01', '10.00', '10000.00', 15, 'Islampur', 'J. Co. Distribution', 1, 1, '2015-10-27 07:25:53', '2015-10-27 01:25:53'),
(30, 'B-24323', '103.125.874', '2015-10-27', '1970-01-01', '10.00', '5000.00', 15, 'English Road', 'Bimal Chandra Mondol', 1, 1, '2015-10-27 08:14:00', '2015-10-27 02:14:00'),
(31, 'B-29873', '1541203206080001', '2015-10-27', '2015-10-27', '10.00', '1000.00', 16, 'Dolaikhal Road', 'J. Co. Distribution', 1, 1, '2015-10-27 10:01:38', '2015-10-27 04:01:38');

-- --------------------------------------------------------

--
-- Table structure for table `bankbook`
--

CREATE TABLE IF NOT EXISTS `bankbook` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `baccid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `checkno` text COLLATE utf32_bin NOT NULL,
  `dc` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `bankbook`
--

INSERT INTO `bankbook` (`id`, `vid`, `baccid`, `sid`, `cid`, `checkno`, `dc`, `amount`, `userid`, `created_at`, `updated_at`) VALUES
(1, 27, 28, 11, 0, '54174444', 0, '0.00', 1, '2015-09-21', '2015-09-21'),
(2, 28, 28, 0, 26, '251487', 0, '0.00', 1, '2015-09-21', '2015-09-21'),
(3, 29, 2, 11, 0, '666', 0, '0.00', 1, '2015-09-21', '2015-09-21'),
(4, 31, 4, 11, 0, '777', 0, '0.00', 1, '2015-09-21', '2015-09-21'),
(5, 32, 28, 11, 0, '111111', 0, '0.00', 1, '2015-09-21', '2015-09-21'),
(6, 39, 4, 11, 0, '', 0, '0.00', 1, '2015-10-14', '2015-10-14'),
(7, 40, 9, 0, 26, '', 0, '0.00', 1, '2015-10-14', '2015-10-14'),
(8, 46, 9, 0, 1, '', 0, '0.00', 1, '2015-10-14', '2015-10-14'),
(9, 53, 2, 0, 29, '1544444', 0, '0.00', 1, '2015-10-15', '2015-10-15'),
(10, 3, 2, 0, 33, '444', 0, '0.00', 1, '2015-10-15', '2015-10-15'),
(11, 6, 9, 0, 30, '', 0, '0.00', 1, '2015-10-17', '2015-10-17'),
(12, 9, 13, 0, 0, '12541210', 0, '0.00', 1, '2015-10-21', '2015-10-21'),
(13, 10, 2, 0, 0, '141141', 0, '0.00', 1, '2015-10-21', '2015-10-21'),
(14, 11, 2, 2, 0, '', 0, '0.00', 1, '2015-10-21', '2015-10-21'),
(15, 16, 2, 0, 0, '124511', 0, '0.00', 1, '2015-10-21', '2015-10-21'),
(16, 22, 10, 0, 0, '54356', 0, '0.00', 1, '2015-10-25', '2015-10-25'),
(17, 23, 4, 0, 0, '21344', 0, '0.00', 1, '2015-10-25', '2015-10-25'),
(18, 24, 16, 0, 0, '43322', 0, '0.00', 1, '2015-10-25', '2015-10-25'),
(19, 36, 2, 0, 0, '1254814', 0, '0.00', 1, '2015-10-26', '2015-10-26'),
(20, 37, 2, 0, 0, '12541', 0, '0.00', 1, '2015-10-26', '2015-10-26'),
(21, 7, 2, 0, 0, '685414', 0, '0.00', 1, '2015-10-26', '2015-10-26'),
(22, 8, 4, 0, 0, '6514114', 0, '0.00', 1, '2015-10-26', '2015-10-26'),
(23, 10, 2, 0, 0, '76565', 0, '0.00', 1, '2015-10-26', '2015-10-26'),
(24, 11, 4, 0, 0, '5465443', 0, '0.00', 1, '2015-10-26', '2015-10-26'),
(25, 13, 2, 4, 0, '', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(26, 17, 29, 0, 0, '251010', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(27, 18, 29, 0, 0, '4578141', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(28, 19, 4, 0, 0, '6358741', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(29, 20, 29, 4, 0, '148411', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(30, 21, 29, 0, 33, '48745777', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(31, 23, 30, 4, 0, '2154841', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(32, 24, 30, 0, 1, '', 1, '0.00', 1, '2015-10-27', '2015-10-27'),
(33, 25, 30, 0, 0, '141444', 1, '0.00', 1, '2015-10-27', '2015-10-27'),
(34, 26, 30, 0, 0, '154441', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(35, 27, 30, 0, 0, '14441', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(36, 28, 30, 0, 0, '1444', 1, '0.00', 1, '2015-10-27', '2015-10-27'),
(37, 28, 2, 0, 0, '1444', 0, '0.00', 1, '2015-10-27', '2015-10-27'),
(38, 29, 31, 4, 0, '43556', 0, '5600.00', 1, '2015-10-27', '2015-10-27'),
(39, 30, 31, 0, 31, '343654', 1, '4500.00', 1, '2015-10-27', '2015-10-27'),
(40, 31, 31, 0, 0, '34545', 1, '3200.00', 1, '2015-10-27', '2015-10-27'),
(41, 32, 31, 0, 0, '34567', 0, '7600.00', 1, '2015-10-27', '2015-10-27'),
(42, 33, 31, 0, 0, '3412', 1, '4300.00', 1, '2015-10-27', '2015-10-27'),
(43, 33, 2, 0, 0, '3412', 0, '4300.00', 1, '2015-10-27', '2015-10-27'),
(44, 34, 2, 0, 0, '34545', 1, '7500.00', 1, '2015-10-27', '2015-10-27'),
(45, 34, 31, 0, 0, '34545', 0, '7500.00', 1, '2015-10-27', '2015-10-27'),
(46, 40, 2, 0, 1, '', 1, '18000.00', 1, '2015-10-28', '2015-10-28'),
(47, 42, 4, 11, 0, '', 0, '59000.00', 1, '2015-10-28', '2015-10-28'),
(48, 43, 4, 0, 0, '76767', 1, '59000.00', 1, '2015-10-28', '2015-10-28'),
(49, 44, 9, 0, 0, '878787', 0, '23000.00', 1, '2015-10-28', '2015-10-28'),
(50, 45, 10, 0, 0, '6767676', 1, '78000.00', 1, '2015-10-28', '2015-10-28'),
(51, 45, 11, 0, 0, '6767676', 0, '78000.00', 1, '2015-10-28', '2015-10-28'),
(52, 48, 31, 0, 38, '1245555', 1, '4000.00', 1, '2015-10-29', '2015-10-29'),
(53, 49, 31, 0, 0, '15471414', 1, '4000.00', 1, '2015-10-29', '2015-10-29'),
(54, 50, 31, 0, 0, '14444', 0, '4000.00', 1, '2015-10-29', '2015-10-29'),
(55, 61, 4, 11, 0, '5454545', 0, '12000.00', 1, '2015-11-01', '2015-11-01'),
(56, 63, 13, 0, 30, '565666', 1, '89000.00', 1, '2015-11-01', '2015-11-01');

-- --------------------------------------------------------

--
-- Table structure for table `bankinfo`
--

CREATE TABLE IF NOT EXISTS `bankinfo` (
`id` int(11) NOT NULL,
  `name` text NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bankinfo`
--

INSERT INTO `bankinfo` (`id`, `name`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'NBL', 1, '2015-08-30 09:37:43', '2015-08-30 03:37:43'),
(3, 'IBBL', 1, '2015-08-16 04:49:03', '2015-08-15 22:49:03'),
(4, 'Rupali Bank', 1, '2015-08-11 04:30:49', '2015-08-11 04:30:49'),
(5, 'Dhaka Bank', 1, '2015-08-19 23:32:53', '2015-08-19 23:32:53'),
(6, 'Dhaka Bank', 1, '2015-08-26 23:08:47', '2015-08-26 23:08:47'),
(9, 'Sonali Bank', 1, '2015-08-30 02:45:25', '2015-08-30 02:45:25'),
(11, 'NBL1', 1, '2015-08-30 03:36:50', '2015-08-30 03:36:50'),
(12, 'Prime Bank', 1, '2015-09-20 03:30:03', '2015-09-20 03:30:03'),
(13, 'City Bank', 1, '2015-09-21 00:32:15', '2015-09-21 00:32:15'),
(14, 'Jamuna Bank ', 1, '2015-10-27 00:11:58', '2015-10-27 00:11:58'),
(15, 'Eastern Bank Limited', 1, '2015-10-27 01:23:38', '2015-10-27 01:23:38'),
(16, 'BRAC Bank ', 1, '2015-10-27 03:58:13', '2015-10-27 03:58:13');

-- --------------------------------------------------------

--
-- Table structure for table `banktitle`
--

CREATE TABLE IF NOT EXISTS `banktitle` (
`id` int(10) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banktitle`
--

INSERT INTO `banktitle` (`id`, `name`) VALUES
(1, 'j. Co. Battery Engineering Works'),
(2, 'Agrani Traders'),
(3, 'Bimal Chandra Mondol'),
(4, 'J. Co. Distribution');

-- --------------------------------------------------------

--
-- Table structure for table `billspay`
--

CREATE TABLE IF NOT EXISTS `billspay` (
`id` int(11) NOT NULL,
  `purchaseid` int(11) NOT NULL,
  `purchasedate` datetime DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `file` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `billspay`
--

INSERT INTO `billspay` (`id`, `purchaseid`, `purchasedate`, `amount`, `file`, `userid`, `created_at`, `updated_at`) VALUES
(19, 9, '2015-08-04 00:00:00', 10000, '64957.jpg', 1, '2015-08-04', '2015-08-04'),
(20, 10, '2015-08-04 00:00:00', 50000, '18524.jpg', 1, '2015-08-04', '2015-08-04'),
(21, 16, '2015-08-06 00:00:00', 5101, '85649.jpg', 1, '2015-08-06', '2015-08-06'),
(22, 18, '2015-08-06 00:00:00', 5000, '77701.jpg', 1, '2015-08-06', '2015-08-06'),
(27, 33, '0000-00-00 00:00:00', 99999999, '32438.jpg', 1, '2015-08-13', '2015-08-16'),
(28, 34, '0000-00-00 00:00:00', 888, '20787.jpg', 1, '2015-08-13', '2015-08-13');

-- --------------------------------------------------------

--
-- Table structure for table `coa`
--

CREATE TABLE IF NOT EXISTS `coa` (
`id` int(11) NOT NULL,
  `coacode` text COLLATE utf32_bin NOT NULL,
  `code` text COLLATE utf32_bin NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `openbalance` decimal(10,2) NOT NULL,
  `description` text COLLATE utf32_bin NOT NULL,
  `coatypeid` int(11) NOT NULL,
  `increasetypeid` int(11) NOT NULL,
  `taxrateid` int(11) NOT NULL,
  `fixedstatus` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `coa`
--

INSERT INTO `coa` (`id`, `coacode`, `code`, `name`, `openbalance`, `description`, `coatypeid`, `increasetypeid`, `taxrateid`, `fixedstatus`, `userid`, `created_at`, `updated_at`) VALUES
(1, '0', 'A-1483858223', 'Cash A/C', '10000.00', 'Cash A/C', 3, 3, 1, 1, 1, '2015-10-27 06:33:30', '2015-10-27 00:33:30'),
(2, '0', 'A-1187056164', 'Cash Collection A/C', '410000.00', 'Cash Collection', 11, 1, 1, 1, 1, '2015-09-28 04:21:50', '2015-09-27 22:21:50'),
(3, '0', 'A-1295454592', 'Cash Payment A/C', '6949419.00', 'Cash Payment A/C', 11, 1, 1, 1, 1, '2015-10-06 06:44:11', '2015-10-06 00:44:11'),
(4, '0', 'A-1778186978', 'Bkash A/C', '15035500.00', 'Bkash A/C', 11, 1, 1, 1, 1, '2015-09-28 04:22:46', '2015-09-27 22:22:46'),
(5, '0', 'A-1547883103', 'SAP A/C', '225500.00', 'SAP A/C', 11, 1, 1, 1, 1, '2015-09-28 04:23:09', '2015-09-27 22:23:09'),
(6, '0', 'A-363575874', 'KCS A/C', '209800.00', 'KCS A/C', 11, 1, 2, 1, 1, '2015-09-28 04:23:25', '2015-09-27 22:23:25'),
(7, '0', 'A-1888579212', 'MBank A/C', '30513169.00', 'MBank A/C', 11, 1, 3, 1, 1, '2015-10-26 09:47:03', '2015-10-26 03:47:03'),
(8, '0', 'A-1834208617', 'Bank Collection A/C', '81982904.00', 'Bank Collection A/C', 11, 1, 3, 1, 1, '2015-09-28 04:24:28', '2015-09-27 22:24:28'),
(9, '0', 'A-1250471858', 'Bank Payment A/C', '37305.00', 'Bank Payment A/C', 11, 1, 2, 1, 1, '2015-10-06 06:44:58', '2015-10-06 00:44:58'),
(10, '0', 'A-1033391409', 'Bank A/C', '3073432.00', 'Bank A/C', 7, 1, 3, 1, 1, '2015-09-17 08:40:35', '2015-09-17 05:05:05'),
(11, '0', 'A-1869829128', 'SALES A/C', '82942463.00', 'SALES A/C', 5, 2, 1, 1, 1, '2015-09-17 08:40:49', '2015-09-17 05:05:22'),
(12, '0', 'A-1679066394', 'Purchase A/C', '290200.00', 'Purchase A/C', 6, 1, 3, 1, 1, '2015-09-17 08:41:04', '2015-09-17 05:06:51'),
(13, '0', 'A-109329080', 'Salary A/C', '212725.00', 'Salary A/C', 11, 1, 2, 1, 1, '2015-09-17 08:41:38', '2015-09-17 05:08:39'),
(14, '0', 'C-128', 'CLOSING STOCK', '0.00', 'CLOSING STOCK', 17, 1, 1, 1, 1, '2015-10-03 04:22:32', '0000-00-00 00:00:00'),
(15, '0', 'C-130', 'CLOSING STOCK', '0.00', 'CLOSING STOCK', 18, 2, 1, 1, 0, '2015-10-03 04:25:22', '0000-00-00 00:00:00'),
(17, '0', 'A-46262037', 'EID FESTIVAL A/C', '12432900.00', 'EID FESTIVAL A/C', 11, 1, 1, 0, 1, '2015-09-16 07:33:36', '2015-09-16 07:33:36'),
(18, '0', 'A-208345005', 'CAR EXPS A/C', '138211.00', 'CAR EXPS A/C', 11, 1, 3, 0, 1, '2015-09-16 07:34:37', '2015-09-16 07:34:37'),
(19, '0', 'A-1176566547', 'Donation A/C', '15000.00', 'Donation A/C', 11, 1, 2, 0, 1, '2015-09-16 07:35:23', '2015-09-16 07:35:23'),
(20, '0', 'A-1506797361', 'Gass bill A/C', '39000.00', 'Gass bill A/C', 11, 2, 2, 0, 1, '2015-10-26 08:31:41', '2015-10-26 02:31:41'),
(30, '0', 'A-1112137581', 'Insurance A/C', '505634.00', 'Insurance A/C', 11, 2, 2, 0, 1, '2015-10-26 09:29:50', '2015-10-26 03:29:50'),
(31, '0', 'A-647980927', 'IPS PARTS A/C', '9911481.00', 'IPS PARTS A/C', 11, 2, 1, 0, 1, '2015-10-26 09:30:31', '2015-10-26 03:30:31'),
(32, '0', 'A-1490912068', 'L/C EXPS. A/C', '62400.00', 'L/C EXPS. A/C', 11, 1, 2, 0, 1, '2015-09-17 05:20:37', '2015-09-17 05:20:37'),
(33, '0', 'A-26028801', 'Legal EXPS. A/C', '139500.00', 'Legal EXPS. A/C', 11, 1, 2, 0, 1, '2015-09-17 05:21:51', '2015-09-17 05:21:51'),
(34, '0', 'A-1456048402', 'Mobile Subsidy A/C', '12100.00', 'Mobile Subsidy A/C', 11, 1, 3, 0, 1, '2015-09-17 05:23:03', '2015-09-17 05:23:03'),
(35, '0', 'A-592991262', 'Overtime Exps A/C', '692900.00', 'Overtime Exps A/C', 11, 1, 2, 0, 1, '2015-09-17 05:24:22', '2015-09-17 05:24:22'),
(36, '0', 'A-980970469', 'Personal A/C', '60894800.00', 'Personal A/C', 11, 1, 1, 0, 1, '2015-09-17 05:25:41', '2015-09-17 05:25:41'),
(37, '0', 'A-718215587', 'PICKUP EXPS A/C', '7215090.00', 'PICKUP EXPS A/C', 11, 1, 2, 0, 1, '2015-09-17 05:27:18', '2015-09-17 05:27:18'),
(38, '', 'A-22781', 'Mobile Phone A/C', '20000.00', '', 11, 1, 1, 0, 1, '2015-09-19 05:09:51', '2015-09-19 05:09:51'),
(39, '', 'B-23335', '22111050000083(Prime Bank)', '0.00', '1111', 7, 3, 1, 0, 1, '2015-09-01 03:33:26', '2015-09-01 03:33:26'),
(40, '', 'A-20560', 'Durga Puza', '5000.00', 'aaa', 11, 1, 1, 0, 1, '2015-09-20 05:40:53', '2015-09-20 05:40:53'),
(41, '', 'CUS:10', 'Munlight Battery', '0.00', '111', 8, 1, 1, 0, 1, '2015-09-04 23:00:14', '2015-09-04 23:00:14'),
(42, '', 's-102', 'Asha Trading', '0.00', '111', 9, 1, 0, 0, 1, '2015-09-21 00:31:32', '2015-09-21 00:31:32'),
(43, '', 'B-19328', '1401614747001(City Bank)', '0.00', '111', 7, 3, 1, 0, 1, '2015-09-21 00:34:32', '2015-09-21 00:34:32'),
(44, '', 'A-1981', 'Furniture A/C', '0.00', 'Furniture A/C', 3, 1, 1, 0, 1, '2015-10-03 05:32:27', '2015-10-03 05:32:27'),
(45, '', 'A-24674', 'Land A/C', '0.00', 'Land A/C', 3, 1, 1, 0, 1, '2015-10-03 05:34:28', '2015-10-03 05:34:28'),
(46, '', 'A-22656', 'Audit Exps.', '29020000.00', 'Audit Exps.', 11, 1, 1, 0, 1, '2015-10-04 23:38:54', '2015-10-04 23:38:54'),
(47, '', 'A-12289', 'Biddut Mondal', '4000000.00', 'Biddut Mondal', 11, 1, 1, 0, 1, '2015-10-04 23:39:49', '2015-10-04 23:39:49'),
(48, '', 'A-10533', 'Bonus A/C', '99999999.99', 'Bonus A/C', 11, 1, 2, 0, 1, '2015-10-04 23:40:36', '2015-10-04 23:40:36'),
(49, '', 'A-28326', 'BSCIC NUR MOH A/C', '2408300.00', 'BSCIC NUR MOH A/C', 11, 1, 2, 0, 1, '2015-10-04 23:41:38', '2015-10-04 23:41:38'),
(50, '', 'A-24807', 'CAR EXPS A/C', '13821100.00', 'CAR EXPS A/C', 11, 1, 3, 0, 1, '2015-10-04 23:42:29', '2015-10-04 23:42:29'),
(51, '', 'A-31482', 'WASA A/C', '7630000.00', 'WASA A/C', 11, 1, 1, 0, 1, '2015-10-04 23:43:26', '2015-10-04 23:43:26'),
(52, '', 'A-14507', 'WAGES A/C ', '0.00', 'WAGES A/C ', 11, 1, 2, 0, 1, '2015-10-04 23:44:35', '2015-10-04 23:44:35'),
(53, '', 'A-14025', 'Travelling EXPS A/C', '0.00', 'Travelling EXPS A/C', 11, 1, 2, 0, 1, '2015-10-04 23:46:38', '2015-10-04 23:46:38'),
(54, '', 'A-24249', 'Training Exps', '0.00', 'Training Exps', 11, 1, 2, 0, 1, '2015-10-04 23:47:18', '2015-10-04 23:47:18'),
(55, '', 'A-25342', 'Personal A/C', '0.00', 'Personal A/C', 11, 1, 2, 0, 1, '2015-10-05 05:48:26', '2015-10-04 23:48:26'),
(56, '', 'A-14943', 'Salary A/C', '0.00', 'Salary A/C', 11, 1, 3, 0, 1, '2015-10-04 23:49:07', '2015-10-04 23:49:07'),
(57, '', 'A-31916', 'Refund A/C', '0.00', 'Refund A/C', 11, 1, 2, 0, 1, '2015-10-04 23:49:44', '2015-10-04 23:49:44'),
(58, '', 'A-11637', 'Rent Exps.', '0.00', 'Rent Exps.', 11, 1, 1, 0, 1, '2015-10-04 23:50:20', '2015-10-04 23:50:20'),
(59, '', 'A-16846', 'Printing and Stationary A/C', '0.00', 'Printing and Stationary A/C', 11, 1, 2, 0, 1, '2015-10-04 23:52:10', '2015-10-04 23:52:10'),
(60, '', 'A-7547', 'Overtime Exps', '0.00', 'Overtime Exps', 11, 1, 2, 0, 1, '2015-10-04 23:54:59', '2015-10-04 23:54:59'),
(61, '', 'A-14232', 'Legal Exps', '0.00', 'Legal Exps', 11, 1, 2, 0, 1, '2015-10-04 23:55:53', '2015-10-04 23:55:53'),
(62, '', 'A-11249', 'Mobile Subsidy A/C', '0.00', 'Mobile Subsidy A/C', 11, 1, 2, 0, 1, '2015-10-04 23:56:40', '2015-10-04 23:56:40'),
(63, '', 'A-21462', 'Gas Bill A/C', '0.00', 'Gas Bill A/C', 11, 1, 1, 0, 1, '2015-10-05 06:39:22', '2015-10-05 00:39:22'),
(64, '', 'A-5719', 'Insurance A/C', '0.00', 'Insurance A/C', 11, 1, 2, 0, 1, '2015-10-04 23:58:05', '2015-10-04 23:58:05'),
(65, '', 'A-13636', 'Fuel Exps A/C', '0.00', 'Fuel Exps A/C', 11, 1, 1, 0, 1, '2015-10-04 23:58:49', '2015-10-04 23:58:49'),
(66, '', 'A-16113', 'li', '25000.00', 'rty', 19, 3, 1, 0, 1, '2015-10-12 02:27:10', '2015-10-12 02:27:10'),
(67, '', 'C-1630', 'Mr. ABCD', '0.00', 'Regular Customer', 8, 1, 1, 0, 1, '2015-10-15 00:32:43', '2015-10-15 00:32:43'),
(68, '', 'C-29143', 'Mr. ABCDE', '0.00', 'reeee', 8, 2, 1, 0, 1, '2015-10-15 03:24:19', '2015-10-15 03:24:19'),
(69, '', 'C-22747', 'MR.L', '0.00', '111', 8, 2, 1, 0, 1, '2015-09-01 03:56:01', '2015-09-01 03:56:01'),
(70, '', 'C-32456', 'MR.w', '0.00', '111', 8, 2, 0, 0, 1, '2015-09-15 03:59:17', '2015-09-15 03:59:17'),
(71, '', 'C-1339', 'Customer B', '0.00', 'qqqq', 8, 2, 1, 0, 1, '2015-10-15 04:30:58', '2015-10-15 04:30:58'),
(72, '', 'C-24522', 'Customer A', '0.00', 'aaa', 8, 2, 1, 0, 1, '2015-10-15 04:31:45', '2015-10-15 04:31:45'),
(73, '', 'A-2196', 'Bank OD A/C', '0.00', 'Not ', 1, 1, 1, 0, 1, '2015-10-17 00:30:20', '2015-10-17 00:30:20'),
(74, '', 's-0244744', 'S A Corporation', '0.00', 'Regular Supplier', 9, 1, 1, 0, 1, '2015-10-20 23:12:07', '2015-10-20 23:12:07'),
(75, '', 'A-20832', 'CAR EXPS A/C', '0.00', 'CAR EXPS A/C', 3, 2, 0, 0, 1, '2015-10-26 03:31:45', '2015-10-26 03:31:45'),
(76, '', 'A-5208', 'iugrtuhurance A/C', '777.00', 'fyht', 19, 1, 0, 0, 1, '2015-10-27 00:01:20', '2015-10-27 00:01:20'),
(77, '', 'B-21944', '103.107.7841(Eastern Bank Limited)', '0.00', 'bank account', 7, 3, 1, 0, 1, '2015-10-27 01:25:53', '2015-10-27 01:25:53'),
(78, '', 'B-24323', '103.125.874(Eastern Bank Limited)', '0.00', 'bank account', 7, 3, 1, 0, 1, '2015-10-27 02:14:00', '2015-10-27 02:14:00'),
(79, '', 'B-29873', '1541203206080001(BRAC Bank )', '0.00', 'bank', 7, 3, 1, 0, 1, '2015-10-27 04:01:38', '2015-10-27 04:01:38'),
(80, '', 'C-6692', 'MR. W', '0.00', 'ff', 8, 1, 1, 0, 1, '2015-10-29 00:04:43', '2015-10-29 00:04:43'),
(81, '', 'C-17078', 'Info', '0.00', 'All Payment', 8, 1, 1, 0, 1, '2015-10-29 04:16:48', '2015-10-29 04:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `coatype`
--

CREATE TABLE IF NOT EXISTS `coatype` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `coatype`
--

INSERT INTO `coatype` (`id`, `name`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'Loan(Liability)', 1, '2015-09-15 04:18:39', '0000-00-00 00:00:00'),
(2, 'Current Liabilities', 1, '2015-09-15 04:19:00', '0000-00-00 00:00:00'),
(3, 'Fixed Assets', 1, '2015-09-15 04:19:25', '0000-00-00 00:00:00'),
(4, 'Current Assets', 1, '2015-09-15 04:19:58', '0000-00-00 00:00:00'),
(5, 'Sales Accounts', 1, '2015-09-15 04:20:23', '0000-00-00 00:00:00'),
(6, 'Purchase Acccounts', 1, '2015-09-15 04:20:55', '0000-00-00 00:00:00'),
(7, 'Bank Accounts', 1, '2015-09-15 04:21:38', '0000-00-00 00:00:00'),
(8, 'Customer Accounts', 1, '2015-09-15 04:22:07', '0000-00-00 00:00:00'),
(9, 'Supplier Accounts', 1, '2015-09-15 04:22:26', '0000-00-00 00:00:00'),
(10, 'Direct Expenses', 1, '2015-09-15 04:23:15', '2015-09-14 01:01:32'),
(11, 'Indirect Expenses', 1, '2015-09-15 04:23:38', '2015-09-14 01:07:39'),
(12, 'Profit & Loss Acccount', 1, '2015-09-15 04:24:51', '2015-09-14 01:10:33'),
(13, 'Equity', 1, '2015-09-15 04:27:16', '2015-09-14 01:11:24'),
(14, 'Cost Of goods', 1, '2015-09-15 04:28:04', '2015-09-14 01:18:10'),
(15, 'Salary Accounts', 1, '2015-09-15 04:28:32', '2015-09-14 05:56:22'),
(16, 'Accounts Payble', 1, '2015-09-15 05:53:35', '2015-09-14 23:52:04'),
(17, 'Opening Stock', 1, '2015-10-03 04:18:05', '0000-00-00 00:00:00'),
(18, 'Closing Stock', 1, '2015-10-03 04:18:05', '0000-00-00 00:00:00'),
(19, 'overload', 1, '2015-10-12 02:23:12', '2015-10-12 02:23:12');

-- --------------------------------------------------------

--
-- Table structure for table `companyprofile`
--

CREATE TABLE IF NOT EXISTS `companyprofile` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `file` text COLLATE utf32_bin NOT NULL,
  `address` text COLLATE utf32_bin NOT NULL,
  `telephone` text COLLATE utf32_bin NOT NULL,
  `mobile` text COLLATE utf32_bin NOT NULL,
  `email` text COLLATE utf32_bin NOT NULL,
  `url` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `companyprofile`
--

INSERT INTO `companyprofile` (`id`, `name`, `file`, `address`, `telephone`, `mobile`, `email`, `url`, `userid`, `created_at`, `updated_at`) VALUES
(5, 'J. Co. Battery Engineering Works', '88902.jpg', '44,Tanti Bazar ,Dhaka-1100 , Bangladesd', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-17 23:46:58', '2015-08-17 23:46:58'),
(6, 'J. Co. Battery Engineering Works', '54559.jpg', '44,Tanti Bazar ,Dhaka-1100 , Bangladesd', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-17 23:49:07', '2015-08-17 23:49:07'),
(7, 'J. Co. Battery Engineering Works', '83577.jpg', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-18 06:41:43', '2015-08-17 23:49:53'),
(8, 'J. Co. Battery Engineering Works', '72930.jpg', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-18 22:07:24', '2015-08-18 22:07:24'),
(9, 'J. Co. Battery Engineering Works', '98071.jpg', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-23 03:46:54', '2015-08-23 03:46:54'),
(10, 'J. Co. Battery Engineering Works', '20258.png', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-23 03:47:27', '2015-08-23 03:47:27'),
(11, 'P. Co. Battery Engineering Works', '22707.png', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-23 21:54:11', '2015-08-23 21:54:11'),
(12, 'J Co. Battery Engineering Works', '68627.png', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-23 21:54:46', '2015-08-23 21:54:46'),
(13, 'J Co. Battery Engineering Works', '80506.jpg', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-24 05:34:54', '2015-08-24 05:34:54'),
(14, 'J Co. Battery Engineering Works', '37315.png', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-08-24 05:35:43', '2015-08-24 05:35:43'),
(15, 'J Co. Battery Engineering Works', '12920.png', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-09-07 01:04:23', '2015-09-07 01:04:23'),
(16, 'J Co. Battery Engineering Works', '38549.png', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-09-07 01:06:24', '2015-09-07 01:06:24'),
(17, 'J Co. Battery Engineering Works', '86805.gif', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-09-07 01:06:52', '2015-09-07 01:06:52'),
(18, 'J Co. Battery Engineering Works', '95581.png', '44,Tanti Bazar ,Dhaka-1100 , Bangladesh', '+88 02-5739539', '+880 1977991099', 'jcobattery1@gmail.com , info@jcobattery.com , info1@jcobattery.com', 'http://www.jcobattery.com', 1, '2015-09-07 01:08:43', '2015-09-07 01:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
`id` int(11) NOT NULL,
  `code` text COLLATE utf32_bin NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `preaddress` text COLLATE utf32_bin NOT NULL,
  `peraddress` text COLLATE utf32_bin NOT NULL,
  `phone` text COLLATE utf32_bin NOT NULL,
  `email` text COLLATE utf32_bin NOT NULL,
  `fax` text COLLATE utf32_bin NOT NULL,
  `url` text COLLATE utf32_bin NOT NULL,
  `openbalance` decimal(10,2) NOT NULL,
  `creditlimit` decimal(10,2) NOT NULL,
  `lastdue` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bstatus` int(11) NOT NULL,
  `coastatus` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `name`, `preaddress`, `peraddress`, `phone`, `email`, `fax`, `url`, `openbalance`, `creditlimit`, `lastdue`, `bstatus`, `coastatus`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'CUS:10', 'Munlight Battery', 'Dhaka', 'Cox''s Bazar', '12345', 'mohsin@gmail.com', '98765', 'http://mohsin', '0.00', '50000.00', '26600.00', 1, 1, 1, '2015-08-02', '2015-11-04'),
(2, 'CUS:102 ', 'MBI International', 'Dhaka', 'Shutrapur', '98765', 'subrata@gmail.com', '12345', 'http://subrata', '2518.00', '250000.00', '3718.00', 1, 0, 1, '2015-08-02', '2015-11-04'),
(3, 'S-103', 'Emon Battery', 'Gulshan 1,Dhaka-1212', 'Gulshan 1,Dhaka-1212', '019144462111', 'palash.ahmed@gmail.com', '+1 760 284 3360', '', '100000.00', '80000.00', '0.00', 0, 0, 1, '2015-08-14', '2015-09-03'),
(4, 'C-32740', 'Beu Battery', 'gulshan', 'gulshan', '0178886344', 'jco@gmail.com', '', '', '10500.00', '500000.00', '0.00', 0, 0, 1, '2015-08-18', '2015-10-03'),
(5, 'C-10046', 'Mitu Battery Store', 'Dhaka ', 'Dhaka', '', '', '', '', '99999999.99', '0.00', '0.00', 0, 0, 1, '2015-08-20', '2015-08-31'),
(6, 'C-13500', 'SB battery', 'Gulsan, dhaka, bangladesh', 'Gulsan, dhaka, bangladesh', '1234567890', 'sb@gmail.com', '12345', '', '100000.00', '50000.00', '256550.00', 1, 0, 1, '2015-08-23', '2015-11-01'),
(7, 'C-25851', 'TNT', 'gulshan, Dhaka 1212', 'gulshan, Dhaka 1212', '0178886344', 'sascafs@outlook.com', '', 'http://www.sascafs.com', '19490.00', '20000.00', '0.00', 0, 0, 1, '2015-08-27', '2015-09-20'),
(10, 'C-4846', 'Babu Bhai', 'Lalbag', 'Lalbag', '21441111', '', '', '', '500000.00', '200000.00', '0.00', 0, 0, 1, '2015-08-30', '2015-09-13'),
(11, 'C-1229', 'Dhaka Battery Comilla', 'Comilla', 'Comilla', '21412414', '', '', '', '100000.00', '50000.00', '0.00', 0, 0, 1, '2015-09-03', '2015-09-08'),
(12, 'C-26156', 'sasa', 'asas', 'asas', 'saas', 'sascafs@outlook.com', '', '', '30.20', '4.00', '0.00', 0, 0, 1, '2015-09-07', '2015-09-13'),
(13, 'C-5059', 'Joy', '', '', '', '', '', '', '1000.00', '5000.00', '0.00', 0, 0, 0, '2015-09-07', '2015-09-07'),
(14, 'C-32523', 'SAM IT', 'Dhaka', 'Dhaka', '0178554444', '', '', '', '48500.00', '250000.00', '0.00', 0, 0, 1, '2015-09-07', '2015-09-13'),
(15, 'C-17504', 'ymm', 'Dhaka', 'Dhaka', '0178886344', '', '', '', '2500.00', '50000.00', '0.00', 0, 0, 1, '2015-09-07', '2015-09-07'),
(16, 'C-22328', 'ss', 'dd', 'dd', '0123564+645+6', '', '', '', '50000.00', '250000.00', '0.00', 0, 0, 1, '2015-09-07', '2015-09-07'),
(18, 'C-13430', 'test', '', '', '', '', '', '', '50000.00', '10000.00', '0.00', 0, 0, 0, '2015-09-08', '2015-09-08'),
(19, 'C-12815', 'qqq', '', '', '', '', '', '', '2.30', '5.10', '0.00', 0, 0, 0, '2015-09-09', '2015-09-09'),
(20, 'C-12134', 'last122', '', '', '', '', '', '', '1111.00', '222.00', '0.00', 0, 0, 0, '2015-09-09', '2015-09-09'),
(21, 'C-27081', 'aa', '', '', '', '', '', '', '6.00', '8.00', '0.00', 0, 0, 0, '2015-09-09', '2015-09-09'),
(22, 'C-10311', 'Sumon', '', '', '', '', '', '', '100000.00', '1000.00', '0.00', 0, 0, 0, '2015-09-15', '2015-09-15'),
(23, 'C-10311', 'Sumon', '', '', '', '', '', '', '100000.00', '1000.00', '0.00', 0, 0, 0, '2015-09-15', '2015-09-15'),
(24, 'C-4259', 'Ireen', '', '', '', '', '', '', '18000.80', '8000.00', '0.00', 0, 0, 0, '2015-09-15', '2015-09-15'),
(25, 'C-16212', 'jijtf', '', '', '', '', '', '', '10000.99', '1000.80', '0.00', 0, 0, 0, '2015-09-15', '2015-09-15'),
(26, 'C-7966', 'Saidul', 'df', '', '1111-111-1111', '', '', '', '-22676023.00', '5000.00', '0.00', 0, 1, 1, '2015-09-15', '2015-10-14'),
(27, 'C-30527', 'Subrata Sarker', 'Sutrapur', 'Sutrapur', '1111-111-1111', '', '', '', '24500.00', '50000.00', '0.00', 0, 0, 1, '2015-09-20', '2015-09-20'),
(28, 'C-13330', 'MR ABC', 'sss', 'sss', '1111-111-1111', '', '', '', '270000.00', '250000.00', '0.00', 0, 0, 1, '2015-10-14', '2015-10-14'),
(29, 'C-1630', 'Mr. ABCD', 'Dhaka', 'Dhaka', '1111-111-1111', '', '', '', '60000.00', '120000.00', '115000.00', 0, 1, 1, '2015-10-15', '2015-10-15'),
(30, 'C-29143', 'Mr. ABCDE', '', '', '', '', '', '', '100000.00', '180000.00', '-95790.00', 0, 1, 0, '2015-10-15', '2015-11-04'),
(31, 'C-22747', 'MR.L', '121212', '454545', '1234-123-1234', 'sa_sarker10@yahoo.com', 'ssss', '', '50000.00', '150000.00', '0.00', 0, 1, 1, '2015-09-01', '2015-10-15'),
(32, 'C-32456', 'MR.w', '111', '111', '1234-123-1234', 'sa_sarker10@yahoo.com', '12345', '', '20000.00', '120000.00', '0.00', 0, 1, 1, '2015-09-15', '2015-10-15'),
(33, 'C-24522', 'Customer A', 'address', 'aaaa', '1111-111-1111', '', '', '', '20000.00', '150000.00', '-50000.00', 0, 1, 1, '2015-10-15', '2015-11-04'),
(34, 'C-1339', 'Customer B', 'address', '', '1111-111-1111', 'as@gmail.com', '', '', '15000.00', '100000.00', '-141300.00', 0, 1, 1, '2015-10-15', '2015-11-01'),
(35, 'C-23659', 'SAS201', 'Dhaka', 'Dhaka', '01793532035', 'sascafs@gmail.com', '', '', '0.00', '0.00', '0.00', 0, 0, 1, '2015-10-25', '2015-10-25'),
(36, 'C-26355', '', '', '', '', '', '', '', '6.00', '5.00', '0.00', 0, 0, 0, '2015-10-28', '2015-10-28'),
(37, 'C-10625', 'MR. T', 'Tanti Bazar', 'Tanti Bazar', '1111-111-1111', '', '', '', '20000.00', '0.00', '49998.00', 0, 0, 1, '2015-10-29', '2015-10-29'),
(38, 'C-6692', 'MR. W', 'Tanti Bazar', 'Tanti Bazar', '1111-111-1111', 'sa_sarker10@yahoo.com', '', '', '15000.00', '0.00', '80000.00', 1, 1, 1, '2015-10-29', '2015-10-29'),
(39, 'C-4087', 'Mr. D', 'Gulshan', '', '', '', '', '', '5400.00', '0.00', '0.00', 0, 0, 0, '2015-10-29', '2015-10-29'),
(40, 'C-27662', 'R', 'uu', '', '', '', '', '', '0.00', '0.00', '0.00', 0, 0, 0, '2015-10-29', '2015-10-29'),
(41, 'C-17078', 'Info', 'Dhaka,', '', '', '', '', '', '7500.00', '0.00', '0.00', 1, 1, 0, '2015-10-29', '2015-10-29'),
(42, 'C-17902', 'asd', 'Dhaka', '', '', '', '', '', '2000.00', '0.00', '0.00', 0, 0, 0, '2015-11-01', '2015-11-01'),
(43, 'C-8160', 'gh', '546546', '', '5464', 'q@g.com', '', 'http://htrh', '2000.00', '4000.00', '0.00', 0, 0, 1, '2015-12-31', '2015-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `customersledger`
--

CREATE TABLE IF NOT EXISTS `customersledger` (
`id` int(11) NOT NULL,
  `rv` int(11) NOT NULL,
  `sv` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `dc` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customersledger`
--

INSERT INTO `customersledger` (`id`, `rv`, `sv`, `cid`, `amount`, `dc`, `created_at`, `updated_at`) VALUES
(1, 0, 16, 1, '9500.00', 0, '2015-09-08', '2015-09-08'),
(2, 0, 17, 2, '40000.00', 0, '2015-09-08', '2015-09-08'),
(3, 23, 0, 1, '50000.00', 0, '2015-09-08', '2015-09-08'),
(4, 24, 0, 2, '10000.00', 0, '2015-09-08', '2015-09-08'),
(5, 25, 0, 2, '3000.00', 0, '2015-09-08', '2015-09-08'),
(8, 28, 0, 2, '77000.00', 0, '2015-09-08', '2015-09-08'),
(10, 30, 0, 2, '89000.00', 0, '2015-09-08', '2015-09-08'),
(11, 31, 0, 3, '60000.00', 0, '2015-09-08', '2015-09-08'),
(12, 32, 0, 5, '70000.00', 0, '2015-09-08', '2015-09-08'),
(13, 33, 0, 4, '9000.00', 0, '2015-09-08', '2015-09-08'),
(14, 0, 18, 17, '29499.45', 0, '2015-09-08', '2015-09-08'),
(15, 0, 19, 17, '59500.00', 0, '2015-09-08', '2015-09-08'),
(16, 34, 0, 17, '200000.00', 0, '2015-09-08', '2015-09-08'),
(17, 0, 20, 17, '15000.00', 0, '2015-09-08', '2015-09-08'),
(18, 0, 21, 17, '1000.00', 0, '2015-09-08', '2015-09-08'),
(19, 40, 0, 6, '4500.00', 0, '2015-09-08', '2015-09-08'),
(20, 0, 22, 11, '40000.00', 0, '2015-09-08', '2015-09-08'),
(21, 0, 23, 11, '4994445.00', 0, '2015-09-08', '2015-09-08'),
(22, 0, 24, 2, '3300.00', 0, '2015-09-08', '2015-09-08'),
(23, 51, 0, 1, '800000.00', 0, '2015-09-09', '2015-09-09'),
(24, 0, 25, 2, '9250.00', 0, '2015-09-09', '2015-09-09'),
(25, 0, 26, 2, '8000.00', 0, '2015-09-10', '2015-09-10'),
(26, 0, 27, 1, '471.07', 0, '2015-09-10', '2015-09-10'),
(27, 0, 28, 7, '7000.00', 0, '2015-09-10', '2015-09-10'),
(28, 0, 29, 4, '12500.00', 0, '2015-09-12', '2015-09-12'),
(30, 57, 0, 14, '10000.00', 0, '2015-09-13', '2015-09-13'),
(31, 0, 31, 2, '28000.00', 0, '2015-09-13', '2015-09-13'),
(32, 58, 0, 2, '10000.00', 0, '2015-09-13', '2015-09-13'),
(33, 0, 32, 1, '8000.00', 0, '2015-09-13', '2015-09-13'),
(34, 59, 0, 1, '5000.00', 0, '2015-09-13', '2015-09-13'),
(35, 60, 0, 1, '2000.00', 0, '2015-09-13', '2015-09-13'),
(36, 61, 0, 1, '5000.00', 0, '2015-09-14', '2015-09-14'),
(38, 63, 0, 1, '1110.00', 0, '2015-09-14', '2015-09-14'),
(40, 65, 0, 2, '70500.00', 0, '2015-09-14', '2015-09-14'),
(41, 66, 0, 2, '20000.00', 0, '2015-09-14', '2015-09-14'),
(42, 67, 0, 2, '10000.00', 0, '2015-09-14', '2015-09-14'),
(43, 0, 33, 1, '53744.00', 0, '2015-09-14', '2015-09-14'),
(44, 0, 33, 1, '4000.00', 0, '2015-09-15', '2015-09-15'),
(45, 0, 34, 14, '5000.00', 0, '2015-09-15', '2015-09-15'),
(46, 0, 35, 2, '16000.00', 0, '2015-09-15', '2015-09-15'),
(47, 0, 36, 26, '3500.00', 0, '2015-09-15', '2015-09-15'),
(48, 70, 0, 26, '1000.00', 0, '2015-09-15', '2015-09-15'),
(49, 0, 37, 26, '10000.00', 0, '2015-09-15', '2015-09-15'),
(50, 0, 38, 26, '6000.00', 0, '2015-09-15', '2015-09-15'),
(51, 0, 39, 26, '6000.00', 0, '2015-09-15', '2015-09-15'),
(52, 0, 40, 26, '6000.00', 0, '2015-09-15', '2015-09-15'),
(53, 0, 41, 26, '700.00', 0, '2015-09-15', '2015-09-15'),
(54, 0, 42, 26, '1000.00', 0, '2015-09-15', '2015-09-15'),
(55, 71, 0, 26, '500.00', 0, '2015-09-15', '2015-09-15'),
(56, 0, 43, 26, '4502.00', 0, '2015-09-15', '2015-09-15'),
(57, 0, 44, 26, '4500.00', 0, '2015-09-15', '2015-09-15'),
(58, 72, 0, 26, '5000.00', 0, '2015-09-15', '2015-09-15'),
(59, 0, 45, 26, '4000.00', 0, '2015-09-15', '2015-09-15'),
(60, 73, 0, 26, '2000.00', 0, '2015-09-15', '2015-09-15'),
(61, 0, 46, 26, '1000.00', 0, '2015-09-15', '2015-09-15'),
(62, 0, 47, 2, '7000.00', 0, '2015-09-15', '2015-09-15'),
(63, 75, 0, 26, '300.00', 0, '2015-09-16', '2015-09-16'),
(64, 76, 0, 26, '400.00', 0, '2015-09-16', '2015-09-16'),
(65, 77, 0, 26, '20000.00', 0, '2015-09-16', '2015-09-16'),
(67, 79, 0, 26, '6000.00', 0, '2015-09-16', '2015-09-16'),
(69, 81, 0, 26, '3000.00', 0, '2015-09-16', '2015-09-16'),
(70, 84, 0, 26, '5000.00', 0, '2015-09-16', '2015-09-16'),
(73, 87, 0, 26, '9000.00', 0, '2015-09-16', '2015-09-16'),
(74, 88, 0, 26, '4000.00', 0, '2015-09-16', '2015-09-16'),
(75, 89, 0, 26, '7000.00', 0, '2015-09-16', '2015-09-16'),
(76, 90, 0, 26, '60000.00', 0, '2015-09-16', '2015-09-16'),
(78, 92, 0, 26, '1000.00', 0, '2015-09-16', '2015-09-16'),
(81, 95, 0, 26, '9000.00', 0, '2015-09-16', '2015-09-16'),
(82, 96, 0, 26, '5000.00', 0, '2015-09-16', '2015-09-16'),
(83, 97, 0, 26, '39000.00', 0, '2015-09-16', '2015-09-16'),
(84, 98, 0, 26, '87000.00', 0, '2015-09-16', '2015-09-16'),
(85, 99, 0, 26, '65000.00', 0, '2015-09-16', '2015-09-16'),
(86, 100, 0, 26, '90876.00', 0, '2015-09-16', '2015-09-16'),
(87, 101, 0, 26, '38000.00', 0, '2015-09-16', '2015-09-16'),
(88, 102, 0, 26, '67000.00', 0, '2015-09-16', '2015-09-16'),
(89, 103, 0, 26, '3000.00', 0, '2015-09-16', '2015-09-16'),
(90, 104, 0, 26, '12000.00', 0, '2015-09-16', '2015-09-16'),
(91, 105, 0, 26, '23000.00', 0, '2015-09-16', '2015-09-16'),
(92, 106, 0, 26, '23000.00', 0, '2015-09-16', '2015-09-16'),
(93, 107, 0, 26, '34000.00', 0, '2015-09-16', '2015-09-16'),
(94, 108, 0, 26, '23100.00', 0, '2015-09-16', '2015-09-16'),
(95, 109, 0, 26, '45000.00', 0, '2015-09-16', '2015-09-16'),
(98, 120, 0, 26, '21.00', 0, '2015-09-17', '2015-09-17'),
(99, 123, 0, 26, '21.00', 0, '2015-09-17', '2015-09-17'),
(100, 124, 0, 26, '21.00', 0, '2015-09-17', '2015-09-17'),
(101, 125, 0, 26, '23.00', 0, '2015-09-17', '2015-09-17'),
(102, 126, 0, 26, '44.00', 0, '2015-09-17', '2015-09-17'),
(103, 129, 0, 26, '21.00', 0, '2015-09-17', '2015-09-17'),
(104, 132, 0, 26, '21.00', 0, '2015-09-17', '2015-09-17'),
(105, 134, 0, 26, '10000.00', 0, '2015-09-17', '2015-09-17'),
(106, 135, 0, 26, '5000.00', 0, '2015-09-17', '2015-09-17'),
(107, 136, 0, 26, '2900.00', 0, '2015-09-17', '2015-09-17'),
(108, 137, 0, 26, '3400.00', 0, '2015-09-17', '2015-09-17'),
(109, 138, 0, 26, '45000.00', 0, '2015-09-17', '2015-09-17'),
(110, 139, 0, 26, '67000.00', 0, '2015-09-17', '2015-09-17'),
(111, 140, 0, 26, '9000.00', 0, '2015-09-17', '2015-09-17'),
(112, 141, 0, 26, '23000.00', 0, '2015-09-17', '2015-09-17'),
(113, 142, 0, 26, '5000.00', 0, '2015-09-17', '2015-09-17'),
(114, 144, 0, 26, '56000.00', 0, '2015-09-17', '2015-09-17'),
(115, 145, 0, 26, '908000.00', 0, '2015-09-17', '2015-09-17'),
(116, 0, 48, 7, '4500.00', 0, '2015-09-17', '2015-09-17'),
(117, 0, 49, 4, '7500.00', 0, '2015-09-19', '2015-09-19'),
(118, 0, 50, 27, '4500.00', 0, '2015-09-20', '2015-09-20'),
(119, 147, 0, 26, '2000.00', 0, '2015-09-01', '2015-09-01'),
(120, 151, 0, 26, '3000.00', 0, '2015-09-08', '2015-09-08'),
(121, 154, 0, 26, '2000.00', 0, '2015-09-20', '2015-09-20'),
(122, 155, 0, 26, '9999.00', 0, '2015-09-20', '2015-09-20'),
(123, 156, 0, 26, '444.00', 0, '2015-09-20', '2015-09-20'),
(124, 157, 0, 26, '21.00', 0, '2015-09-20', '2015-09-20'),
(125, 158, 0, 26, '12.00', 0, '2015-09-20', '2015-09-20'),
(126, 159, 0, 26, '9999.00', 0, '2015-09-20', '2015-09-20'),
(128, 0, 52, 7, '-10.00', 0, '2015-09-20', '2015-09-20'),
(129, 160, 0, 26, '9999.00', 0, '2015-09-21', '2015-09-21'),
(130, 161, 0, 26, '444.00', 0, '2015-09-21', '2015-09-21'),
(131, 162, 0, 26, '12000000.00', 0, '2015-09-21', '2015-09-21'),
(132, 163, 0, 26, '2000.00', 0, '2015-09-21', '2015-09-21'),
(133, 164, 0, 26, '67000.00', 0, '2015-09-21', '2015-09-21'),
(134, 165, 0, 26, '12000.00', 0, '2015-09-21', '2015-09-21'),
(135, 167, 0, 26, '2000.00', 0, '2015-09-21', '2015-09-21'),
(136, 168, 0, 26, '444.00', 0, '2015-09-21', '2015-09-21'),
(137, 169, 0, 26, '12000.00', 0, '2015-09-21', '2015-09-21'),
(138, 170, 0, 26, '2000.00', 0, '2015-09-21', '2015-09-21'),
(139, 171, 0, 26, '444.00', 0, '2015-09-21', '2015-09-21'),
(140, 172, 0, 26, '7000.00', 0, '2015-09-21', '2015-09-21'),
(141, 173, 0, 26, '78000.00', 0, '2015-09-21', '2015-09-21'),
(142, 174, 0, 26, '9999.00', 0, '2015-09-21', '2015-09-21'),
(143, 175, 0, 26, '7000.00', 0, '2015-09-21', '2015-09-21'),
(144, 176, 0, 26, '9999.00', 0, '2015-09-21', '2015-09-21'),
(145, 177, 0, 26, '45000.00', 0, '2015-09-21', '2015-09-21'),
(146, 178, 0, 26, '2000.00', 0, '2015-09-21', '2015-09-21'),
(147, 179, 0, 26, '56000.00', 0, '2015-09-21', '2015-09-21'),
(148, 180, 0, 26, '67000.00', 0, '2015-09-21', '2015-09-21'),
(149, 181, 0, 26, '56565.00', 0, '2015-09-21', '2015-09-21'),
(150, 182, 0, 26, '7000.00', 0, '2015-09-21', '2015-09-21'),
(151, 183, 0, 26, '7676767.00', 0, '2015-09-21', '2015-09-21'),
(152, 184, 0, 26, '444.00', 0, '2015-09-21', '2015-09-21'),
(153, 185, 0, 26, '56000.00', 0, '2015-09-21', '2015-09-21'),
(154, 186, 0, 26, '34000.00', 0, '2015-09-21', '2015-09-21'),
(155, 1, 0, 26, '10000.00', 0, '2015-09-01', '2015-09-01'),
(156, 2, 0, 26, '20000.00', 0, '2015-09-01', '2015-09-01'),
(157, 3, 0, 26, '7000.00', 0, '2015-09-05', '2015-09-05'),
(158, 4, 0, 1, '3000.00', 0, '2015-09-05', '2015-09-05'),
(160, 6, 0, 1, '8500.00', 0, '2015-09-21', '2015-09-21'),
(161, 12, 0, 1, '666666.00', 0, '2015-09-21', '2015-09-21'),
(162, 13, 0, 1, '7777.00', 0, '2015-09-21', '2015-09-21'),
(163, 16, 0, 1, '8500.00', 0, '2015-09-21', '2015-09-21'),
(164, 17, 0, 1, '888888.00', 0, '2015-09-21', '2015-09-21'),
(165, 20, 0, 1, '111.00', 0, '2015-09-21', '2015-09-21'),
(166, 24, 0, 1, '5000.00', 0, '2015-09-21', '2015-09-21'),
(167, 28, 0, 26, '3000.00', 0, '2015-09-21', '2015-09-21'),
(168, 0, 53, 1, '400.00', 0, '2015-09-22', '2015-09-22'),
(169, 0, 54, 2, '2500.00', 0, '2015-10-03', '2015-10-03'),
(170, 0, 55, 4, '2000.00', 0, '2015-10-03', '2015-10-03'),
(171, 33, 0, 1, '9000000.00', 0, '2015-10-05', '2015-10-05'),
(172, 34, 0, 26, '7000.00', 0, '2015-10-05', '2015-10-05'),
(173, 35, 0, 1, '898989.00', 0, '2015-10-11', '2015-10-11'),
(174, 0, 56, 1, '100000.00', 0, '2015-10-11', '2015-10-11'),
(175, 37, 0, 1, '100000.00', 0, '2015-10-11', '2015-10-11'),
(176, 40, 0, 26, '12000.00', 0, '2015-10-14', '2015-10-14'),
(177, 41, 0, 1, '34000.00', 0, '2015-10-14', '2015-10-14'),
(178, 0, 57, 28, '30000.00', 0, '2015-10-14', '2015-10-14'),
(179, 0, 58, 28, '40000.00', 0, '2015-10-14', '2015-10-14'),
(180, 0, 59, 28, '20000.00', 0, '2015-10-14', '2015-10-14'),
(181, 42, 0, 1, '70000.00', 0, '2015-10-14', '2015-10-14'),
(182, 43, 0, 1, '67000.00', 0, '2015-10-14', '2015-10-14'),
(183, 44, 0, 1, '23000.00', 0, '2015-10-14', '2015-10-14'),
(184, 45, 0, 1, '47000.00', 0, '2015-10-14', '2015-10-14'),
(185, 46, 0, 1, '612000.00', 0, '2015-10-14', '2015-10-14'),
(186, 47, 0, 1, '239000.00', 0, '2015-10-14', '2015-10-14'),
(187, 48, 0, 1, '60000.00', 0, '2015-10-14', '2015-10-14'),
(188, 49, 0, 1, '12000.00', 0, '2015-10-14', '2015-10-14'),
(189, 50, 0, 1, '2390000.00', 0, '2015-10-14', '2015-10-14'),
(190, 51, 0, 1, '435000.00', 0, '2015-10-14', '2015-10-14'),
(191, 0, 60, 29, '30000.00', 0, '2015-10-15', '2015-10-15'),
(192, 0, 61, 29, '20000.00', 0, '2015-10-15', '2015-10-15'),
(193, 0, 62, 29, '5000.00', 0, '2015-10-15', '2015-10-15'),
(194, 52, 0, 29, '50000.00', 0, '2015-10-15', '2015-10-15'),
(195, 53, 0, 29, '45000.00', 0, '2015-10-15', '2015-10-15'),
(196, 54, 0, 29, '20000.00', 0, '2015-10-15', '2015-10-15'),
(197, 0, 63, 30, '60000.00', 0, '2015-10-15', '2015-10-15'),
(198, 0, 64, 30, '16000.00', 0, '2015-10-15', '2015-10-15'),
(199, 55, 0, 30, '100000.00', 0, '2015-10-15', '2015-10-15'),
(200, 56, 0, 30, '76000.00', 0, '2015-10-15', '2015-10-15'),
(201, 0, 1, 30, '30000.00', 0, '2015-10-15', '2015-10-15'),
(202, 0, 2, 30, '30000.00', 0, '2015-10-15', '2015-10-15'),
(203, 1, 0, 30, '100000.00', 0, '2015-10-15', '2015-10-15'),
(204, 2, 0, 30, '60000.00', 0, '2015-10-15', '2015-10-15'),
(205, 0, 3, 1, '30000.00', 0, '2015-09-01', '2015-09-01'),
(206, 0, 4, 1, '50000.00', 0, '2015-09-10', '2015-09-10'),
(207, 0, 1, 31, '20000.00', 0, '2015-09-01', '2015-09-01'),
(208, 0, 2, 32, '30000.00', 0, '2015-09-15', '2015-09-15'),
(209, 0, 3, 31, '20000.00', 0, '2015-10-15', '2015-10-15'),
(210, 0, 4, 31, '20000.00', 0, '2015-10-15', '2015-10-15'),
(211, 0, 5, 32, '10000.00', 0, '2015-10-15', '2015-10-15'),
(212, 0, 6, 32, '10000.00', 0, '2015-10-15', '2015-10-15'),
(213, 0, 7, 31, '16000.00', 0, '2015-10-15', '2015-10-15'),
(214, 1, 0, 32, '50000.00', 0, '2015-10-15', '2015-10-15'),
(215, 0, 8, 33, '30000.00', 0, '2015-10-15', '2015-10-15'),
(216, 0, 9, 33, '9500.00', 0, '2015-10-15', '2015-10-15'),
(217, 0, 10, 34, '30000.00', 0, '2015-10-15', '2015-10-15'),
(218, 0, 11, 34, '30000.00', 0, '2015-10-15', '2015-10-15'),
(219, 0, 12, 33, '15000.00', 0, '2015-10-15', '2015-10-15'),
(220, 0, 13, 33, '12000.00', 0, '2015-10-15', '2015-10-15'),
(221, 2, 0, 33, '70000.00', 0, '2015-10-15', '2015-10-15'),
(222, 3, 0, 33, '6500.00', 0, '2015-10-15', '2015-10-15'),
(223, 4, 0, 33, '5000.00', 0, '2015-10-15', '2015-10-15'),
(224, 0, 14, 30, '4349.85', 0, '2015-10-17', '2015-10-17'),
(225, 0, 15, 30, '10000.00', 0, '2015-10-17', '2015-10-17'),
(226, 5, 0, 30, '100000.00', 0, '2015-10-17', '2015-10-17'),
(227, 6, 0, 30, '114549.85', 0, '2015-10-17', '2015-10-17'),
(228, 0, 1, 30, '30000.00', 0, '2015-10-17', '2015-10-17'),
(229, 0, 2, 30, '30000.00', 0, '2015-10-17', '2015-10-17'),
(230, 0, 3, 30, '20000.00', 0, '2015-10-17', '2015-10-17'),
(231, 0, 4, 1, '10000.00', 0, '2015-09-01', '2015-09-01'),
(232, 0, 5, 1, '22000.00', 0, '2015-09-15', '2015-09-15'),
(233, 0, 6, 33, '50000.00', 0, '2015-09-01', '2015-09-01'),
(234, 0, 7, 34, '24000.00', 0, '2015-09-15', '2015-09-15'),
(235, 0, 8, 33, '65000.00', 0, '2015-10-17', '2015-10-17'),
(236, 0, 9, 1, '0.00', 0, '2015-10-21', '2015-10-21'),
(237, 8, 0, 30, '20000.00', 0, '2015-10-21', '2015-10-21'),
(238, 12, 0, 34, '89000.00', 0, '2015-10-21', '2015-10-21'),
(239, 13, 0, 31, '23000.00', 0, '2015-10-21', '2015-10-21'),
(240, 14, 0, 32, '56000.00', 0, '2015-10-21', '2015-10-21'),
(241, 17, 0, 29, '23900.00', 0, '2015-10-25', '2015-10-25'),
(242, 18, 0, 30, '12000.00', 0, '2015-10-25', '2015-10-25'),
(243, 19, 0, 32, '23000.00', 0, '2015-10-25', '2015-10-25'),
(244, 20, 0, 31, '87000.00', 0, '2015-10-25', '2015-10-25'),
(245, 21, 0, 33, '32000.00', 0, '2015-10-25', '2015-10-25'),
(246, 25, 0, 1, '3200.00', 0, '2015-10-25', '2015-10-25'),
(247, 26, 0, 1, '2500.00', 0, '2015-10-25', '2015-10-25'),
(248, 27, 0, 29, '7800.00', 0, '2015-10-25', '2015-10-25'),
(249, 28, 0, 31, '2500.00', 0, '2015-10-25', '2015-10-25'),
(250, 30, 0, 30, '78000.00', 0, '2015-10-25', '2015-10-25'),
(251, 31, 0, 29, '9000.00', 0, '2015-10-25', '2015-10-25'),
(252, 32, 0, 26, '78000.00', 0, '2015-10-25', '2015-10-25'),
(253, 33, 0, 31, '7000.00', 0, '2015-10-25', '2015-10-25'),
(254, 34, 0, 1, '34000.00', 0, '2015-10-26', '2015-10-26'),
(255, 35, 0, 31, '8000.00', 0, '2015-10-26', '2015-10-26'),
(256, 39, 0, 1, '7800.00', 0, '2015-10-26', '2015-10-26'),
(257, 40, 0, 1, '6300.00', 0, '2015-10-26', '2015-10-26'),
(258, 41, 0, 30, '3200.00', 0, '2015-10-26', '2015-10-26'),
(259, 42, 0, 26, '6200.00', 0, '2015-10-26', '2015-10-26'),
(260, 2, 0, 1, '3000.00', 0, '2015-10-26', '2015-10-26'),
(261, 3, 0, 30, '1500.00', 0, '2015-10-26', '2015-10-26'),
(262, 4, 0, 34, '2300.00', 0, '2015-10-26', '2015-10-26'),
(263, 5, 0, 30, '6390.00', 0, '2015-10-26', '2015-10-26'),
(264, 6, 0, 1, '1700.00', 0, '2015-10-26', '2015-10-26'),
(265, 12, 0, 1, '25000.00', 0, '2015-10-27', '2015-10-27'),
(266, 15, 0, 1, '50000.00', 0, '2015-10-27', '2015-10-27'),
(267, 16, 0, 1, '1200.00', 0, '2015-10-27', '2015-10-27'),
(268, 21, 0, 33, '20000.00', 0, '2015-10-27', '2015-10-27'),
(269, 24, 0, 1, '3200.00', 0, '2015-10-27', '2015-10-27'),
(270, 30, 0, 31, '4500.00', 0, '2015-10-27', '2015-10-27'),
(271, 35, 0, 29, '18000.00', 0, '2015-10-28', '2015-10-28'),
(272, 36, 0, 1, '67000.00', 0, '2015-10-28', '2015-10-28'),
(273, 37, 0, 30, '59000.00', 0, '2015-10-28', '2015-10-28'),
(274, 38, 0, 33, '54000.00', 0, '2015-10-28', '2015-10-28'),
(275, 39, 0, 29, '23000.00', 0, '2015-10-28', '2015-10-28'),
(276, 40, 0, 1, '18000.00', 0, '2015-10-28', '2015-10-28'),
(277, 0, 10, 2, '20700.00', 0, '2015-10-28', '2015-10-28'),
(278, 0, 11, 1, '10000.00', 0, '2015-10-28', '2015-10-28'),
(279, 0, 12, 1, '325000.00', 0, '2015-10-28', '2015-10-28'),
(280, 0, 13, 1, '30000.00', 0, '2015-10-28', '2015-10-28'),
(281, 0, 15, 1, '4000.00', 0, '2015-10-28', '2015-10-28'),
(282, 0, 18, 1, '15000.00', 0, '2015-10-28', '2015-10-28'),
(283, 0, 19, 1, '39000.00', 0, '2015-10-28', '2015-10-28'),
(284, 0, 20, 1, '20000.00', 0, '2015-10-28', '2015-10-28'),
(285, 0, 21, 37, '9000.00', 0, '2015-10-29', '2015-10-29'),
(286, 0, 23, 37, '2500.00', 0, '2015-10-29', '2015-10-29'),
(287, 0, 24, 38, '60000.00', 0, '2015-10-29', '2015-10-29'),
(288, 0, 25, 38, '4000.00', 0, '2015-10-29', '2015-10-29'),
(289, 46, 0, 38, '10000.00', 1, '2015-10-29', '2015-10-29'),
(290, 47, 0, 38, '5000.00', 1, '2015-10-29', '2015-10-29'),
(291, 48, 0, 38, '4000.00', 0, '2015-10-29', '2015-10-29'),
(292, 51, 0, 38, '10000.00', 0, '2015-10-29', '2015-10-29'),
(293, 52, 0, 38, '10000.00', 0, '2015-10-29', '2015-10-29'),
(294, 53, 0, 38, '10000.00', 0, '2015-10-29', '2015-10-29'),
(295, 54, 0, 38, '15000.00', 0, '2015-10-29', '2015-10-29'),
(296, 55, 0, 38, '15000.00', 0, '2015-10-29', '2015-10-29'),
(297, 0, 26, 38, '38000.00', 0, '2015-10-29', '2015-10-29'),
(298, 0, 27, 1, '500.00', 0, '2015-10-29', '2015-10-29'),
(299, 0, 28, 38, '66000.00', 0, '2015-10-29', '2015-10-29'),
(300, 0, 29, 38, '100000.00', 0, '2015-10-29', '2015-10-29'),
(301, 0, 30, 37, '29998.00', 0, '2015-10-29', '2015-10-29'),
(302, 0, 31, 38, '47000.00', 0, '2015-10-29', '2015-10-29'),
(303, 56, 0, 38, '120000.00', 1, '2015-10-29', '2015-10-29'),
(304, 0, 32, 38, '50000.00', 0, '2015-10-29', '2015-10-29'),
(305, 57, 0, 38, '130000.00', 0, '2015-10-29', '2015-10-29'),
(306, 0, 33, 38, '59000.00', 0, '2015-10-29', '2015-10-29'),
(307, 58, 0, 38, '74000.00', 0, '2015-10-29', '2015-10-29'),
(308, 0, 34, 38, '45000.00', 0, '2015-10-29', '2015-10-29'),
(309, 59, 0, 38, '60000.00', 1, '2015-10-29', '2015-10-29'),
(310, 0, 35, 38, '80000.00', 0, '2015-10-29', '2015-10-29'),
(311, 0, 36, 1, '53600.00', 0, '2015-10-29', '2015-10-29'),
(312, 0, 37, 41, '45750.00', 0, '2015-10-29', '2015-10-29'),
(313, 60, 0, 41, '53250.00', 0, '2015-10-29', '2015-10-29'),
(314, 0, 38, 1, '12000.00', 0, '2015-10-31', '2015-10-31'),
(315, 0, 39, 2, '27000.00', 0, '2015-11-01', '2015-11-01'),
(316, 0, 40, 2, '50000.00', 0, '2015-11-01', '2015-11-01'),
(317, 63, 0, 30, '89000.00', 0, '2015-11-01', '2015-11-01'),
(318, 64, 0, 34, '65000.00', 1, '2015-11-01', '2015-11-01'),
(319, 65, 0, 33, '67000.00', 0, '2015-11-01', '2015-11-01'),
(320, 66, 0, 1, '78000.00', 0, '2015-11-01', '2015-11-01'),
(321, 67, 0, 32, '23000.00', 0, '2015-11-01', '2015-11-01'),
(322, 68, 0, 29, '78900.00', 0, '2015-11-01', '2015-11-01'),
(323, 0, 41, 6, '156550.00', 0, '2015-11-01', '2015-11-01'),
(324, 0, 42, 1, '20000.00', 0, '2015-11-03', '2015-11-03'),
(325, 69, 0, 1, '4000.00', 0, '2015-11-02', '2015-11-02'),
(326, 70, 0, 30, '6700.00', 0, '2015-11-04', '2015-11-04'),
(327, 71, 0, 33, '12000.00', 0, '2015-11-04', '2015-11-04'),
(328, 0, 43, 1, '1000.00', 0, '2015-11-04', '2015-11-04'),
(329, 0, 44, 1, '15000.00', 0, '2015-11-04', '2015-11-04'),
(330, 0, 45, 1, '10000.00', 0, '2015-11-04', '2015-11-04'),
(331, 0, 46, 2, '1200.00', 0, '2015-11-04', '2015-11-04'),
(332, 0, 47, 1, '25000.00', 0, '2015-11-04', '2015-11-04');

-- --------------------------------------------------------

--
-- Table structure for table `employeeinfo`
--

CREATE TABLE IF NOT EXISTS `employeeinfo` (
`id` int(11) NOT NULL,
  `name` text NOT NULL,
  `designation` text NOT NULL,
  `joindate` date NOT NULL,
  `peraddress` varchar(60) NOT NULL,
  `preaddress` varchar(60) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `house_rent` decimal(10,2) NOT NULL,
  `medical_expense` decimal(10,2) NOT NULL,
  `food_expense` decimal(10,2) NOT NULL,
  `conveyance` decimal(10,2) NOT NULL,
  `entertain_allowance` decimal(10,2) NOT NULL,
  `total_salary` decimal(10,2) NOT NULL,
  `employeetype` varchar(40) NOT NULL,
  `file` text NOT NULL,
  `uid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employeeinfo`
--

INSERT INTO `employeeinfo` (`id`, `name`, `designation`, `joindate`, `peraddress`, `preaddress`, `basic_salary`, `house_rent`, `medical_expense`, `food_expense`, `conveyance`, `entertain_allowance`, `total_salary`, `employeetype`, `file`, `uid`, `userid`, `created_at`, `updated_at`) VALUES
(2, 'Employee_1', 'Sales Executive', '2015-09-02', 'Rajshahi', 'Dhaka', '5555.00', '0.00', '0.00', '0.00', '0.00', '0.00', '5555.00', '6', '93277.jpg', 17, 1, '2015-09-14 09:13:31', '2015-09-07 01:13:24'),
(4, 'Employee_2', 'Senior Executive', '2015-09-02', 'Comilla', 'Comilla', '1213.00', '0.00', '0.00', '0.00', '0.00', '0.00', '1213.00', '3', '34372.png', 17, 1, '2015-09-14 09:13:40', '2015-09-07 01:13:51'),
(9, 'Employee_3', 'Executive', '2015-09-02', 'Dhaka', 'Dhaka', '10000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '10000.00', '3', '39122.gif', 17, 1, '2015-09-14 09:14:01', '2015-09-07 01:13:04'),
(10, 'Employee_4', 'Executive', '2015-09-07', 'Dhaka', 'Comilla', '12345.00', '0.00', '0.00', '0.00', '0.00', '0.00', '12345.00', '3', '72140.jpg', 2, 1, '2015-09-14 09:14:11', '2015-09-07 02:26:15'),
(11, 'Amran', 'PHP Programmer', '2015-05-02', 'Nakhal para', 'Nakhal para', '10000.00', '4000.00', '2000.00', '2000.00', '1000.00', '1000.00', '20000.00', 'Fresher', '75924.jpg', 17, 1, '2015-09-13 23:34:10', '2015-09-13 23:34:10'),
(12, 'Mohsin', 'Marketing Executive', '2015-04-01', 'Middle Badda', 'Middle Badda', '9000.00', '3000.00', '1500.00', '1500.00', '2000.00', '1000.00', '18000.00', 'Executive', '33211.jpg', 1, 1, '2015-09-14 03:23:31', '2015-09-14 03:23:31'),
(13, 'Timon', 'PHP Programmer', '2015-09-14', 'Rajshahi', 'Dhaka', '15000.00', '4000.00', '1000.00', '3000.00', '1000.00', '1000.00', '25000.00', 'Fresher', '27232.jpg', 17, 1, '2015-09-14 05:14:17', '2015-09-14 05:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `employeesal`
--

CREATE TABLE IF NOT EXISTS `employeesal` (
`id` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `pid` int(11) NOT NULL,
  `vdate` date NOT NULL,
  `description` varchar(60) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employeesal`
--

INSERT INTO `employeesal` (`id`, `eid`, `amount`, `pid`, `vdate`, `description`, `userid`, `created_at`, `updated_at`) VALUES
(1, 4, '200.00', 2, '2015-09-09', '114444', 1, '2015-09-09', '2015-09-09'),
(4, 2, '5.00', 4, '2015-09-09', 'we', 1, '2015-09-09', '2015-09-09'),
(5, 9, '5000.00', 3, '2015-09-09', '441111', 1, '2015-09-09', '2015-09-09'),
(6, 4, '3.00', 3, '2015-09-09', '222sss', 1, '2015-09-09', '2015-09-09'),
(7, 2, '2000.00', 1, '2015-09-14', 'ssss', 1, '2015-09-14', '2015-09-14'),
(8, 2, '3000.00', 3, '2015-09-14', 'fff', 1, '2015-09-14', '2015-09-14'),
(9, 2, '3000.00', 3, '2015-09-14', 'fff', 1, '2015-09-14', '2015-09-14'),
(10, 11, '20000.00', 1, '2015-09-14', 'Monthly Salary', 1, '2015-09-14', '2015-09-14'),
(11, 13, '7500.00', 2, '2015-09-14', 'Bonus', 1, '2015-09-14', '2015-09-14'),
(12, 11, '20000.00', 1, '2015-09-15', 'Monthly Salary', 1, '2015-09-15', '2015-09-15'),
(13, 10, '12345.00', 1, '2015-09-15', 'www', 1, '2015-09-15', '2015-09-15'),
(14, 2, '500.00', 2, '2015-09-15', 'bonus', 1, '2015-09-15', '2015-09-15'),
(15, 2, '2000.00', 3, '2015-09-17', 'Overtime Exps A/C', 1, '2015-09-17', '2015-09-17'),
(16, 10, '2000.00', 5, '2015-10-19', 'na', 1, '2015-10-19', '2015-10-19'),
(17, 2, '10000.00', 1, '2015-10-19', '11111', 1, '2015-10-19', '2015-10-19');

-- --------------------------------------------------------

--
-- Table structure for table `factioyitems`
--

CREATE TABLE IF NOT EXISTS `factioyitems` (
`id` int(11) NOT NULL,
  `itemsid` int(11) NOT NULL,
  `slno` text COLLATE utf32_bin NOT NULL,
  `salesid` int(11) DEFAULT NULL,
  `no` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `feedback` text COLLATE utf32_bin NOT NULL,
  `sale_product` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `factioyitems`
--

INSERT INTO `factioyitems` (`id`, `itemsid`, `slno`, `salesid`, `no`, `status`, `feedback`, `sale_product`, `userid`, `created_at`, `updated_at`) VALUES
(1, 1, 'TAEL0200001', NULL, 1, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(2, 1, 'TAEL0200002', NULL, 2, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(3, 1, 'TAEL0200003', NULL, 3, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(4, 1, 'TAEL0200004', NULL, 4, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(5, 1, 'TAEL0200005', NULL, 5, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(6, 1, 'TAEL0200006', NULL, 6, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(7, 1, 'TAEL0200007', NULL, 7, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(8, 1, 'TAEL0200008', NULL, 8, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(9, 1, 'TAEL0200009', NULL, 9, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(10, 1, 'TAEL0200010', NULL, 10, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(11, 1, 'TAEL0200011', NULL, 11, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(12, 1, 'TAEL0200012', NULL, 12, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(13, 1, 'TAEL0200013', NULL, 13, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(14, 1, 'TAEL0200014', 44, 14, 1, '', 1, 3, '2015-11-02', '2015-11-04'),
(15, 1, 'TAEL0200015', 44, 15, 1, '', 1, 3, '2015-11-02', '2015-11-04'),
(16, 25, 'BAEL0200001', NULL, 1, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(17, 25, 'BAEL0200002', NULL, 2, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(18, 25, 'BAEL0200003', NULL, 3, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(19, 25, 'BAEL0200004', NULL, 4, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(20, 25, 'BAEL0200005', NULL, 5, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(21, 25, 'BAEL0200006', NULL, 6, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(22, 25, 'BAEL0200007', NULL, 7, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(23, 25, 'BAEL0200008', NULL, 8, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(24, 25, 'BAEL0200009', NULL, 9, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(25, 25, 'BAEL0200010', NULL, 10, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(26, 25, 'BAEL0200011', NULL, 11, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(27, 25, 'BAEL0200012', NULL, 12, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(28, 28, 'BAEL0200013', NULL, 13, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(29, 28, 'BAEL0200014', NULL, 14, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(30, 28, 'BAEL0200015', NULL, 15, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(31, 28, 'BAEL0200016', NULL, 16, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(32, 28, 'BAEL0200017', NULL, 17, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(33, 28, 'BAEL0200018', NULL, 18, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(34, 28, 'BAEL0200019', NULL, 19, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(35, 28, 'BAEL0200020', NULL, 20, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(36, 28, 'BAEL0200021', NULL, 21, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(37, 28, 'BAEL0200022', NULL, 22, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(38, 28, 'BAEL0200023', NULL, 23, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(39, 28, 'BAEL0200024', NULL, 24, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(40, 28, 'BAEL0200025', NULL, 25, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(41, 28, 'BAEL0200026', NULL, 26, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(42, 28, 'BAEL0200027', NULL, 27, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(43, 16, 'TAEL0200016', NULL, 16, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(44, 16, 'TAEL0200017', NULL, 17, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(45, 16, 'TAEL0200018', NULL, 18, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(46, 16, 'TAEL0200019', NULL, 19, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(47, 16, 'TAEL0200020', NULL, 20, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(48, 16, 'TAEL0200021', NULL, 21, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(49, 16, 'TAEL0200022', NULL, 22, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(50, 16, 'TAEL0200023', NULL, 23, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(51, 16, 'TAEL0200024', NULL, 24, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(52, 16, 'TAEL0200025', NULL, 25, 1, '', 0, 3, '2015-11-02', '2015-11-02'),
(53, 1, 'BAEL0200028', NULL, 28, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(54, 1, 'BAEL0200029', NULL, 29, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(55, 1, 'BAEL0200030', NULL, 30, 1, '', 0, 2, '2015-11-02', '2015-11-02'),
(56, 1, 'BAEL0200031', 47, 31, 1, '', 1, 2, '2015-11-02', '2015-11-04'),
(57, 1, 'BAEL0200032', 47, 32, 1, '', 1, 2, '2015-11-02', '2015-11-04'),
(58, 16, 'TAEL0400001', NULL, 1, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(59, 16, 'TAEL0400002', NULL, 2, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(60, 16, 'TAEL0400003', NULL, 3, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(61, 16, 'TAEL0400004', NULL, 4, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(62, 16, 'TAEL0400005', NULL, 5, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(63, 17, 'TAEL0400006', NULL, 6, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(64, 17, 'TAEL0400007', NULL, 7, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(65, 17, 'TAEL0400008', NULL, 8, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(66, 17, 'TAEL0400009', NULL, 9, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(67, 17, 'TAEL0400010', NULL, 10, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(68, 17, 'TAEL0400011', NULL, 11, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(69, 17, 'TAEL0400012', NULL, 12, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(70, 17, 'TAEL0400013', NULL, 13, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(71, 17, 'TAEL0400014', NULL, 14, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(72, 17, 'TAEL0400015', NULL, 15, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(73, 17, 'TAEL0400016', NULL, 16, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(74, 17, 'TAEL0400017', NULL, 17, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(75, 45, 'TAEL0400018', NULL, 18, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(76, 45, 'TAEL0400019', NULL, 19, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(77, 45, 'TAEL0400020', NULL, 20, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(78, 45, 'TAEL0400021', NULL, 21, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(79, 45, 'TAEL0400022', NULL, 22, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(80, 45, 'TAEL0400023', NULL, 23, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(81, 45, 'TAEL0400024', NULL, 24, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(82, 45, 'TAEL0400025', NULL, 25, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(83, 45, 'TAEL0400026', NULL, 26, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(84, 45, 'TAEL0400027', NULL, 27, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(85, 45, 'TAEL0400028', NULL, 28, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(86, 45, 'TAEL0400029', NULL, 29, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(87, 45, 'TAEL0400030', NULL, 30, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(88, 45, 'TAEL0400031', NULL, 31, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(89, 45, 'TAEL0400032', NULL, 32, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(90, 45, 'TAEL0400033', NULL, 33, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(91, 45, 'TAEL0400034', NULL, 34, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(92, 45, 'TAEL0400035', NULL, 35, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(93, 45, 'TAEL0400036', NULL, 36, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(94, 45, 'TAEL0400037', NULL, 37, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(95, 45, 'TAEL0400038', NULL, 38, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(96, 45, 'TAEL0400039', NULL, 39, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(97, 45, 'TAEL0400040', NULL, 40, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(98, 45, 'TAEL0400041', NULL, 41, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(99, 45, 'TAEL0400042', NULL, 42, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(100, 45, 'TAEL0400043', NULL, 43, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(101, 45, 'TAEL0400044', NULL, 44, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(102, 45, 'TAEL0400045', NULL, 45, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(103, 45, 'TAEL0400046', NULL, 46, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(104, 45, 'TAEL0400047', NULL, 47, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(105, 45, 'TAEL0400048', NULL, 48, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(106, 45, 'TAEL0400049', NULL, 49, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(107, 45, 'TAEL0400050', NULL, 50, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(108, 45, 'TAEL0400051', NULL, 51, 1, '', 0, 1, '2015-11-04', '2015-11-04'),
(109, 45, 'TAEL0400052', NULL, 52, 1, '', 0, 1, '2015-11-04', '2015-11-04');

-- --------------------------------------------------------

--
-- Table structure for table `increasetype`
--

CREATE TABLE IF NOT EXISTS `increasetype` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `increasetype`
--

INSERT INTO `increasetype` (`id`, `name`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'Debit', 1, '2015-08-12 05:06:47', '0000-00-00 00:00:00'),
(2, 'Credit', 1, '2015-08-12 05:07:04', '0000-00-00 00:00:00'),
(3, 'Debit/Credit', 1, '2015-08-13 04:22:25', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
`id` int(11) NOT NULL,
  `itemssubgroupid` int(11) NOT NULL,
  `code` text COLLATE utf32_bin NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `quantity` int(11) NOT NULL,
  `mesid` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sstatus` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `itemssubgroupid`, `code`, `name`, `quantity`, `mesid`, `price`, `sstatus`, `userid`, `created_at`, `updated_at`) VALUES
(1, 1, 'I-19937', '6 JBT 50A-20AH', 100, 3, '0.00', 1, 1, '2015-08-13', '2015-10-28'),
(2, 1, 'I-6606', '6 JBT 50A-30AH', 200, 3, '0.00', 1, 1, '2015-08-13', '2015-10-28'),
(3, 1, 'I-9638', '6 BBT 100A-60AH', 200, 3, '0.00', 1, 0, '2015-08-13', '2015-08-17'),
(4, 1, 'I-14026', '6 BBT 120A-80AH', 10, 3, '0.00', 1, 0, '2015-08-13', '2015-08-17'),
(5, 1, 'I-4155', '6 JBT 150A-100AH', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(6, 1, 'I-18110', '6 JBT N200A-100AH', 25, 3, '0.00', 1, 0, '2015-08-13', '2015-08-17'),
(7, 2, 'I-25492', '6 BBT 100A-60AH', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(8, 2, 'I-11837', '6 JBT 120A-90AH', 15, 3, '0.00', 1, 0, '2015-08-13', '2015-08-17'),
(9, 2, 'I-30254', '6 JBT 150A-120AH', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(10, 2, 'I-1487', '6 JBT 200A-140AH', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(11, 2, 'I-15196', '6 JBT 200A-160AH', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(12, 2, 'I-32357', '6 JBT N-200AH', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(13, 3, 'I-18234', 'NS 40ZL', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(14, 3, 'I-12267', 'NS 60/L/S', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(15, 3, 'I-16259', 'N50Z/ZL', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(16, 2, 'I-9472', 'NS 70/L', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(17, 2, 'I-13087', 'N70', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(18, 2, 'I-12616', 'NX 120-7/L', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(19, 4, 'I-7137', 'J.CO 70-15', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(20, 4, 'I-26220', 'J.CO 100-17', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(21, 4, 'I-13365', 'J.CO 120-21', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(22, 4, 'I-4298', 'J.CO 200-27', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(23, 4, 'I-25147', 'J.CO 200Z-29', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(24, 5, 'I-32088', 'J.CO EB120', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(25, 5, 'I-24241', 'J.CO EB140', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(26, 6, 'I-26391', 'J.CO Power 50', 50, 3, '0.00', 1, 1, '2015-08-13', '2015-08-13'),
(27, 6, 'I-23419', 'J.Co Power 65', 50, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(28, 6, 'I-20107', 'J.Co Power 75', 50, 3, '0.00', 1, 0, '2015-08-16', '2015-08-16'),
(29, 6, 'I-7576', 'J.Co Power 85', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(30, 7, 'I-6641', '90 Positive Plate', 27, 3, '0.00', 0, 1, '2015-08-16', '2015-11-04'),
(31, 7, 'I-19242', '90 Negative Plate', 375, 3, '0.00', 0, 1, '2015-08-16', '2015-11-04'),
(32, 7, 'I-32027', '80 Positive Plate', 65, 3, '0.00', 0, 1, '2015-08-16', '2015-10-29'),
(33, 7, 'I-31592', '80 Negative Plate', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(34, 7, 'I-16216', '70 Positive Plate', 1100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(35, 7, 'I-26187', '70 Negative Plate', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(36, 7, 'I-32054', 'Faruka Positive Plate', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(37, 7, 'I-6184', 'Faruka Negative Plate', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(38, 7, 'I-30897', 'Auto Positive Plate', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(39, 7, 'I-406', 'Auto Negative Plate', 134, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(40, 7, 'I-4458', 'Tabular Positive Plate(N-120)', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(41, 7, 'I-10561', 'Tabular Negative Plate(N-3.5)', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(42, 7, 'I-26203', 'Tabular Positive Plate(N-1.7)', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(43, 7, 'I-11960', 'Tabular Negative Plate(N-1.5)', 100, 3, '0.00', 0, 1, '2015-08-16', '2015-08-16'),
(44, 8, 'I-32504', 'China Separator', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(45, 8, 'I-20433', 'Glas Separator', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(46, 8, 'I-23094', 'P.E Separator', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(47, 8, 'I-4375', 'Auto Separator', 100, 3, '0.00', 1, 0, '2015-08-16', '2015-08-16'),
(48, 9, 'I-31571', 'Bitumen', 100, 7, '0.00', 1, 1, '2015-08-16', '2015-10-27'),
(49, 10, 'I-8084', 'N-200 Container', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(50, 10, 'I-14909', 'N-150 Container', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(51, 10, 'I-7379', 'N-120 Container', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(52, 10, 'I-10163', 'N-70 Container', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(53, 10, 'I-16400', 'N-50 Container', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(54, 19, 'I-22281', 'Solarland 10wp Panel', 10, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(55, 19, 'I-10710', 'Solarland 20wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(56, 19, 'I-28897', 'Solarland 30wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(57, 19, 'I-32014', 'Solarland 40wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(58, 19, 'I-8560', 'Solarland 50wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(59, 19, 'I-29598', 'Solarland 100wp Panel', 0, 3, '0.00', 1, 0, '2015-08-16', '2015-08-16'),
(60, 19, 'I-13103', 'Solarland 110wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(61, 19, 'I-3711', 'Solarland 120wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(62, 19, 'I-19772', 'Solarland 120wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(63, 19, 'I-3305', 'Solarland 130wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(64, 19, 'I-11630', 'Solarland 200wp Panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(65, 19, 'I-3087', 'Kyocera 40wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(66, 19, 'I-3749', 'Kyocera 50wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(67, 19, 'I-12922', 'Kyocera 65wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(68, 19, 'I-3660', 'GSPV 20wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(69, 19, 'I-28309', 'GSPV 40wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(70, 19, 'I-19974', 'GSPV 50wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(71, 19, 'I-11178', 'GSPV 85wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(72, 19, 'I-5019', 'GSPV 100wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(73, 19, 'I-17208', 'GSPV 130wp panel', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(74, 19, 'I-24337', 'Greenfinity 20wp Panel ', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(75, 19, 'I-22646', 'Greenfinity 40wp Panel ', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(76, 19, 'I-15559', 'Greenfinity 50wp Panel ', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(77, 12, 'I-13044', '400 VA Frendy', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(78, 12, 'I-27521', '700 VA Shark', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(79, 12, 'I-20087', '900 VA Shark', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(80, 12, 'I-31967', '1500 VA Shark', 0, 3, '0.00', 1, 0, '2015-08-16', '2015-08-16'),
(81, 12, 'I-23083', '1000 VA Torque', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(82, 12, 'I-16543', '600 VA Falcon', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(83, 12, 'I-9708', '800 VA Falcon', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(84, 12, 'I-15797', '1400 VA Falcon', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(85, 13, 'I-19756', '5W Charge Controller', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(86, 13, 'I-29685', '10W Charge Controller', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(87, 13, 'I-7242', '20W Charge Controller', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(88, 14, 'I-13528', 'Battery Charger - 60V', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(89, 14, 'I-23373', 'Battery Charger-72V', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(90, 16, 'I-13399', 'Lead', 0, 1, '0.00', 1, 0, '2015-08-16', '2015-08-18'),
(91, 16, 'I-1538', 'Antimony', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(92, 16, 'I-17446', 'Antimonial Lead', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(93, 16, 'I-3796', 'Grey Oxide', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(94, 16, 'I-6269', 'Red Oxide', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(95, 16, 'I-16720', 'Tin', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(96, 16, 'I-13170', 'Arsenic', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(97, 16, 'I-24393', 'Fiber Flock', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(98, 16, 'I-78', 'Pipe Bottom', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(99, 16, 'I-5487', 'Pure Lead', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(100, 16, 'I-8828', 'Parafin Oil', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(101, 16, 'I-9989', 'Hydrocloric Acid', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(102, 16, 'I-23237', 'Cork Powder', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(103, 18, 'I-21338', 'Su-Kam', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(104, 18, 'I-11912', '400 VA Frendy', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(105, 18, 'I-18535', '700 VA Shark', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(106, 18, 'I-27240', '1400 VA Falcon', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(107, 18, 'I-11905', '1500 VA Shark', 100, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(108, 18, 'I-19238', '1000 VA Torque', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(109, 18, 'I-28705', '900 VA Shark', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(110, 17, 'I-9361', 'N-200 Upper Packing', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(111, 17, 'I-13646', 'N-150 Upper Packing', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(112, 17, 'I-31814', 'N-120 Upper Packing', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(113, 17, 'I-28662', 'N-50Upper Packing', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(114, 17, 'I-9839', '12 mm Side Packing', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(115, 17, 'I-28167', '10 mm Side Packing', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(116, 17, 'I-31556', '5 mm Side Packing', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(117, 17, 'I-11060', '20 Ah Carton', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(118, 17, 'I-27997', '100 Ah Carton', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(119, 17, 'I-14877', '60 AH carton', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(120, 17, 'I-24444', '130 AH Carton', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(121, 17, 'I-5842', 'N-200 Cork Sheet', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(122, 17, 'I-14138', 'N-50 Cork Sheet', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(123, 17, 'I-20471', 'N-70 Cork Sheet', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(124, 17, 'I-32356', 'N-150 Cork Sheet', 0, 3, '0.00', 1, 1, '2015-08-16', '2015-08-16'),
(125, 20, 'I-1957', 'Keyboard', 2, 3, '1000.00', 1, 1, '2015-08-27', '2015-08-27'),
(126, 1, 'I-19953', '6 JBT Solar 100', 40, 3, '0.00', 1, 1, '2015-09-13', '2015-09-13'),
(127, 23, 'I-29358', 'fghyft', 2, 7, '700.00', 1, 1, '2015-10-28', '2015-10-28'),
(128, 23, 'I-46', 'iiii55', 3, 7, '200.00', 1, 1, '2015-10-28', '2015-10-28'),
(129, 6, 'I-6894', 'test5', 10, 7, '120.00', 1, 1, '2015-10-28', '2015-10-28'),
(130, 13, 'I-17290', 'gslno', 5, 7, '100.00', 1, 1, '2015-10-28', '2015-10-28'),
(131, 9, 'I-18623', 'Demo Product', 1000, 7, '250.00', 0, 1, '2015-10-28', '2015-10-28');

-- --------------------------------------------------------

--
-- Table structure for table `itemsgroup`
--

CREATE TABLE IF NOT EXISTS `itemsgroup` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `itemsgroup`
--

INSERT INTO `itemsgroup` (`id`, `name`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'Sales', 1, '2015-08-13', '2015-08-30'),
(2, 'Purchase', 1, '2015-08-13', '2015-08-13'),
(3, 'Ohthes Meterials', 1, '2015-08-27', '2015-08-27'),
(4, 'Machinary Product', 1, '2015-08-27', '2015-08-27'),
(7, 'Misc', 1, '2015-09-13', '2015-09-13');

-- --------------------------------------------------------

--
-- Table structure for table `itemssubgroup`
--

CREATE TABLE IF NOT EXISTS `itemssubgroup` (
`id` int(11) NOT NULL,
  `itemgroupid` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `itemssubgroup`
--

INSERT INTO `itemssubgroup` (`id`, `itemgroupid`, `name`, `userid`, `created_at`, `updated_at`) VALUES
(1, 1, 'Solar Battery', 0, '2015-08-13', '2015-08-30'),
(2, 1, 'IPS Battery', 1, '2015-08-13', '2015-08-13'),
(3, 1, 'PC Battery', 1, '2015-08-13', '2015-08-13'),
(4, 1, 'CV Battery', 1, '2015-08-13', '2015-08-13'),
(5, 1, 'EV Battery', 1, '2015-08-13', '2015-08-13'),
(6, 1, 'Rickshaw Battery', 1, '2015-08-13', '2015-08-13'),
(7, 1, 'Plate', 1, '2015-08-13', '2015-08-13'),
(8, 1, 'Seperator', 1, '2015-08-13', '2015-08-13'),
(9, 1, 'Bitumin', 1, '2015-08-13', '2015-08-13'),
(10, 1, 'Container', 0, '2015-08-13', '2015-08-30'),
(11, 1, 'Solar Panel', 1, '2015-08-13', '2015-08-13'),
(12, 1, 'IPS', 1, '2015-08-13', '2015-08-13'),
(13, 1, 'Charge Controller', 1, '2015-08-13', '2015-08-13'),
(14, 1, 'Battery Charger', 1, '2015-08-13', '2015-08-13'),
(16, 2, 'Battery Raw Materials', 0, '2015-08-13', '2015-09-08'),
(17, 2, 'Packing Materials', 1, '2015-08-13', '2015-08-13'),
(18, 2, 'I.P.s', 1, '2015-08-13', '2015-08-13'),
(19, 2, 'Solar Panel', 1, '2015-08-13', '2015-08-13'),
(20, 3, 'Computer Equpments', 1, '2015-08-27', '2015-08-27'),
(22, 2, 'jjj', 1, '2015-08-30', '2015-08-30'),
(23, 2, 'test5', 1, '2015-08-30', '2015-08-30');

-- --------------------------------------------------------

--
-- Table structure for table `measurementgroup`
--

CREATE TABLE IF NOT EXISTS `measurementgroup` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `measurementgroup`
--

INSERT INTO `measurementgroup` (`id`, `name`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'GroupA', 1, '2015-08-02', '2015-08-09'),
(2, 'GroupB', 1, '2015-08-02', '2015-08-09'),
(3, 'GroupC', 1, '2015-08-30', '2015-08-30');

-- --------------------------------------------------------

--
-- Table structure for table `measurementunit`
--

CREATE TABLE IF NOT EXISTS `measurementunit` (
`id` int(11) NOT NULL,
  `mgid` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `measurementunit`
--

INSERT INTO `measurementunit` (`id`, `mgid`, `name`, `userid`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kilogram', 1, '2015-08-03', '2015-08-03'),
(2, 1, 'Liter', 1, '2015-08-03', '2015-08-13'),
(3, 1, 'PCS', 1, '2015-08-03', '2015-08-09'),
(4, 2, 'ounce', 1, '2015-08-27', '2015-08-27'),
(6, 3, 'feet', 1, '2015-08-30', '2015-08-30'),
(7, 3, 'Ton', 1, '2015-10-27', '2015-10-27');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `link` text COLLATE utf32_bin NOT NULL,
  `status` int(10) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `link`, `status`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'Setup', 'setup', 1, 1, '2015-07-08', NULL),
(2, 'Inventory', 'inventory', 1, 1, '2015-07-20', NULL),
(3, 'Sales & Commercial', 'sales', 1, 1, '2015-07-22', NULL),
(4, 'Acccounting', 'inventory', 1, 1, '2015-07-22', NULL),
(5, 'POS', 'pos', 1, 1, '2015-07-08', NULL),
(6, 'Factory Inventory', 'factory', 1, 1, '2015-07-14', NULL),
(7, 'HR And Payroll', 'hr', 1, 1, '2015-07-14', NULL),
(8, 'Ledger Accounting', 'ledgeraccounting', 1, 1, '2015-09-18', NULL),
(9, 'Voucher Information', 'voucher', 1, 1, '2015-09-17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2015_09_01_102309_entrust_setup_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `particulars`
--

CREATE TABLE IF NOT EXISTS `particulars` (
`id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `particulars`
--

INSERT INTO `particulars` (`id`, `name`) VALUES
(1, 'Monthly Salary'),
(2, 'Bonus'),
(3, 'Overtime'),
(4, 'Festival Bonus'),
(5, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `pettycash`
--

CREATE TABLE IF NOT EXISTS `pettycash` (
`id` int(11) NOT NULL,
  `particular` int(255) NOT NULL,
  `increasetype` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `edate` date NOT NULL,
  `instatus` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pettycash`
--

INSERT INTO `pettycash` (`id`, `particular`, `increasetype`, `amount`, `description`, `edate`, `instatus`, `userid`, `created_at`, `updated_at`) VALUES
(1, 19, 0, '1870.00', 'Donation', '0000-00-00', 0, 1, '2015-10-26', '2015-10-26'),
(2, 20, 0, '1570.00', 'Gas bill', '0000-00-00', 0, 1, '2015-10-26', '2015-10-26'),
(3, 31, 0, '3285.00', 'IPS Parts', '0000-00-00', 0, 1, '2015-10-26', '2015-10-26'),
(4, 17, 0, '1230.00', 'eid f', '0000-00-00', 0, 1, '2015-10-26', '2015-10-26'),
(5, 31, 0, '5000.00', 'ips parts', '0000-00-00', 0, 1, '2015-10-26', '2015-10-26');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE IF NOT EXISTS `purchase` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `purchasedate` date DEFAULT NULL,
  `suppliersid` int(11) NOT NULL,
  `suppliersbillno` text COLLATE utf32_bin NOT NULL,
  `suppliersbilldate` date DEFAULT NULL,
  `challanno` text COLLATE utf32_bin NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `gross_total` decimal(10,2) NOT NULL,
  `others_exp` decimal(10,2) NOT NULL,
  `old_sub_total` decimal(10,2) NOT NULL,
  `old_discount` decimal(10,2) NOT NULL,
  `old_gross_total` decimal(10,2) NOT NULL,
  `old_others_exp` decimal(10,2) NOT NULL,
  `status` int(10) DEFAULT '0',
  `cstatus` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`id`, `name`, `purchasedate`, `suppliersid`, `suppliersbillno`, `suppliersbilldate`, `challanno`, `sub_total`, `discount`, `gross_total`, `others_exp`, `old_sub_total`, `old_discount`, `old_gross_total`, `old_others_exp`, `status`, `cstatus`, `userid`, `created_at`, `updated_at`) VALUES
(30, 'P-26698', '2015-09-08', 4, '123456', '2015-09-08', '1234', '115.00', '12.00', '123.00', '20.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-08', '2015-09-08'),
(31, 'P-27913', '2015-09-08', 12, '111', '2015-09-08', '111', '160.00', '16.00', '152.00', '8.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-08', '2015-09-08'),
(32, 'P-21220', '2015-09-08', 26, 'I121212', '2015-09-08', 'I121212', '39902.50', '0.00', '39902.50', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-08', '2015-09-09'),
(33, 'P-13973', '2015-09-08', 4, '14444', '2015-09-08', '14444', '300000.00', '555.00', '299667.00', '222.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-08', '2015-09-10'),
(34, 'P-25695', '2015-09-08', 2, 'aaaa', '2015-09-08', 'dsd', '500000.00', '55.00', '500500.00', '555.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-08', '2015-09-10'),
(35, 'P-12799', '2015-09-08', 27, '14444', '2015-09-08', '155', '50000.00', '5000.00', '47000.00', '2000.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-08', '2015-09-09'),
(36, 'P-16598', '2015-09-09', 2, '1234', '2015-09-09', '1234', '4000.00', '100.00', '4000.00', '100.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-09', '2015-09-09'),
(37, 'P-27003', '2015-09-09', 2, '123', '2015-09-10', '123', '4000.00', '0.00', '4000.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-09', '2015-09-09'),
(38, 'P-3251', '2015-09-09', 2, '123', '2015-09-09', '123', '1111.00', '0.00', '1111.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-09', '2015-09-09'),
(39, 'P-31209', '2015-09-08', 2, '144444', '2015-09-08', '154411', '1000.00', '11.00', '990.00', '1.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-09', '2015-09-09'),
(40, 'P-30926', '2015-09-09', 6, '', '2015-09-09', '', '22500.00', '150.00', '22350.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-09', '2015-09-10'),
(41, 'P-27862', '2015-09-10', 2, '123', '2015-09-10', '123', '1000.00', '10.00', '1000.00', '10.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 1, '2015-09-10', '2015-09-10'),
(42, 'P-22126', '2015-09-10', 2, '1234', '2015-09-10', '1234', '100.00', '20.00', '94.00', '14.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 1, '2015-09-10', '2015-09-10'),
(43, 'P-30469', NULL, 2, '', NULL, '', '376478.31', '0.00', '376478.31', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 1, '2015-09-10', '2015-09-10'),
(44, 'P-31359', NULL, 2, '', NULL, '', '376478.31', '0.00', '376478.31', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 1, '2015-09-10', '2015-09-10'),
(45, 'P-7334', NULL, 2, '', NULL, '', '12007.63', '1500.00', '10707.63', '200.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-10', '2015-09-10'),
(46, 'P-26583', NULL, 2, '', NULL, '', '222.00', '0.00', '222.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-10', '2015-09-13'),
(47, 'P-20325', '2015-09-04', 4, 'billbllbill', '2015-09-01', 'c345', '21872.10', '23.11', '23048.99', '1200.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 1, '2015-09-12', '2015-09-15'),
(48, 'P-20629', '2015-09-01', 33, 'S250', '2015-09-12', 'C250', '9278.75', '1500.00', '10278.75', '2500.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 1, '2015-09-12', '2015-09-13'),
(49, 'P-16233', '2015-09-01', 8, '', NULL, '', '30133.50', '0.00', '30133.50', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 1, '2015-09-12', '2015-09-13'),
(50, 'P-17280', NULL, 2, '', NULL, '', '3022.50', '0.50', '3122.00', '100.00', '0.00', '0.00', '0.00', '0.00', 0, 1, 1, '2015-09-13', '2015-09-13'),
(51, 'P-24000', '2015-09-13', 2, '140', '2015-09-13', '1544', '80006.80', '100.80', '81906.00', '2000.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-13', '2015-09-13'),
(52, 'P-10987', '2015-09-02', 34, 'S100', '2015-09-06', 'C100', '21401.70', '150.00', '22751.70', '1500.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-13', '2015-09-13'),
(53, 'P-21986', '2015-09-15', 2, '12345', '2015-09-17', 'W- 41545', '5000.00', '0.00', '5000.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 1, '2015-09-15', '2015-09-15'),
(54, 'P-21986', '2015-09-15', 2, '12345', '2015-09-17', 'W- 41545', '5000.00', '0.00', '5000.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-15', '2015-09-16'),
(55, 'P-419', '2015-09-16', 11, '', NULL, '', '3000.00', '1200.00', '2000.00', '200.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-16', '2015-09-16'),
(56, 'P-20209', '2015-09-02', 9, '909090', '2015-09-09', '898989', '48000.00', '80.00', '48420.00', '500.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 1, '2015-09-16', '2015-09-16'),
(57, 'P-22431', '2015-09-16', 4, '', NULL, '', '77125.00', '1500.00', '78125.00', '2500.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-16', '2015-09-17'),
(58, 'P-29699', NULL, 2, '', NULL, '', '2880.00', '500.00', '2580.00', '200.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-17', '2015-09-17'),
(59, 'P-10062', '2015-09-20', 2, '', NULL, '', '33330.00', '0.00', '33330.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-09-20', '2015-09-20'),
(60, 'P-4503', '2015-10-03', 2, '14111', '2015-10-03', '1111', '150000.00', '200.00', '150022.00', '222.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-10-03', '2015-10-03'),
(61, 'P-30204', '2015-10-17', 4, '', NULL, '', '3000.00', '0.00', '3000.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-10-17', '2015-10-17'),
(62, 'P-8971', '2015-10-17', 4, '', NULL, '', '1000.00', '0.00', '1000.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-10-17', '2015-10-21'),
(63, 'P-11509', '2015-10-21', 7, '9999', '2015-10-21', '989898', '14000.00', '0.00', '14000.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-10-21', '2015-10-25'),
(64, 'P-9020', '2015-10-21', 4, '', NULL, '', '31259.03', '0.03', '31500.00', '241.00', '0.00', '0.00', '0.00', '0.00', 1, 0, 1, '2015-10-21', '2015-10-21'),
(65, 'P-12307', '2015-10-25', 7, '232323', '2015-10-25', '43434', '11201.50', '0.50', '11400.00', '199.00', '0.00', '0.00', '0.00', '0.00', 0, 0, 1, '2015-10-25', '2015-10-25'),
(66, 'P-11727', '2015-10-25', 8, '', NULL, '', '18000.00', '80.00', '18010.00', '90.00', '18000.00', '80.00', '18010.00', '90.00', 0, 0, 1, '2015-10-25', '2015-10-25'),
(67, 'P-3898', '2015-10-25', 8, '', NULL, '', '12000.00', '20.00', '12000.00', '20.00', '6000.00', '78.00', '6012.00', '90.00', 1, 0, 1, '2015-10-25', '2015-10-25'),
(68, 'P-12226', '2015-10-25', 6, '575757', '2015-10-25', '5757', '21000.00', '80.00', '20997.00', '77.00', '3500.00', '80.00', '3490.00', '70.00', 1, 0, 1, '2015-10-25', '2015-10-25'),
(69, 'P-24848', '2015-10-25', 9, '', NULL, '', '150199.25', '0.25', '150249.00', '50.00', '301727.25', '0.25', '301800.00', '73.00', 1, 0, 1, '2015-10-25', '2015-10-25'),
(70, 'P-1539', '2015-10-25', 9, '', NULL, '', '2400.00', '6.00', '2400.00', '6.00', '1600.00', '8.00', '1600.00', '8.00', 1, 0, 1, '2015-10-25', '2015-10-25'),
(71, 'P-24306', '2015-10-25', 6, '', NULL, '', '25000.00', '0.00', '25000.00', '0.00', '4000.00', '0.00', '4000.00', '0.00', 1, 0, 1, '2015-10-25', '2015-10-25'),
(72, 'P-4571', '2015-10-25', 7, '', NULL, '', '20000.00', '500.00', '20000.00', '500.00', '30000.00', '500.00', '30000.00', '500.00', 0, 1, 1, '2015-10-25', '2015-10-29'),
(73, 'P-249', '2015-10-25', 4, '', NULL, '', '57000.00', '5000.00', '57000.00', '5000.00', '65500.00', '500.00', '70000.00', '5000.00', 1, 0, 1, '2015-10-25', '2015-10-25'),
(74, 'P-28865', '2015-10-01', 6, '', NULL, '', '27825.75', '0.66', '27961.09', '136.00', '27825.75', '0.66', '27961.09', '136.00', 0, 1, 1, '2015-10-25', '2015-10-29'),
(75, 'P-9160', '2015-10-01', 4, '', NULL, '', '37090.24', '0.00', '37090.24', '0.00', '37090.24', '0.00', '37090.24', '0.00', 1, 0, 1, '2015-10-25', '2015-10-25'),
(76, 'P-14502', '2015-10-26', 6, '3333333', '2015-10-26', '33333333', '160000.00', '4.20', '160043.14', '47.34', '50000.00', '7.20', '50060.14', '67.00', 1, 0, 1, '2015-10-26', '2015-10-26'),
(77, 'P-14988', '2015-10-26', 8, '555555', '2015-10-26', '5555555555', '139200.00', '56.34', '139167.09', '23.43', '120000.00', '6.20', '120000.00', '6.20', 1, 0, 1, '2015-10-26', '2015-10-26'),
(78, 'P-1349', '2015-10-27', 2, '', '2015-10-27', '', '69600.00', '500.00', '71100.00', '2000.00', '73500.00', '500.00', '75000.00', '2000.00', 1, 0, 1, '2015-10-27', '2015-10-27'),
(79, 'P-2851', '2015-10-28', 4, '', NULL, '', '27600.00', '2000.00', '30100.00', '4500.00', '142800.00', '2000.00', '145300.00', '4500.00', 1, 0, 1, '2015-10-28', '2015-10-28'),
(80, 'P-22955', '2015-10-29', 26, '44111', '2015-10-30', '25144', '148920.00', '920.00', '150000.00', '2000.00', '148920.00', '920.00', '150000.00', '2000.00', 0, 1, 1, '2015-10-29', '2015-10-29'),
(81, 'P-32524', '2015-10-29', 4, '', NULL, '', '102565.40', '0.40', '103000.00', '435.00', '102565.40', '0.40', '103000.00', '435.00', 1, 0, 1, '2015-10-29', '2015-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `purchasedetails`
--

CREATE TABLE IF NOT EXISTS `purchasedetails` (
`id` int(11) NOT NULL,
  `purchaseid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `old_quantity` decimal(10,2) NOT NULL,
  `mesid` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `old_rate` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `old_amount` decimal(10,2) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `purchasedetails`
--

INSERT INTO `purchasedetails` (`id`, `purchaseid`, `itemid`, `quantity`, `old_quantity`, `mesid`, `rate`, `old_rate`, `amount`, `old_amount`, `userid`, `created_at`, `updated_at`) VALUES
(1, 4, 60, '100.00', '0.00', 3, '1500.00', '0.00', '150000.00', '0.00', 1, '2015-09-06', '2015-09-06'),
(2, 5, 55, '1.00', '0.00', 0, '5000.00', '0.00', '5000.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(3, 6, 55, '1.00', '0.00', 0, '5000.00', '0.00', '5000.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(4, 7, 55, '5.00', '0.00', 0, '5000.00', '0.00', '25000.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(5, 8, 113, '6.00', '0.00', 3, '5.00', '0.00', '30.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(6, 9, 111, '4.00', '0.00', 3, '6.00', '0.00', '24.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(7, 9, 107, '4.00', '0.00', 3, '13.00', '0.00', '52.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(8, 9, 118, '8.00', '0.00', 3, '10.00', '0.00', '80.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(9, 10, 111, '4.00', '0.00', 0, '6.00', '0.00', '24.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(10, 10, 107, '4.00', '0.00', 0, '13.00', '0.00', '52.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(11, 10, 118, '8.00', '0.00', 0, '9.00', '0.00', '72.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(12, 11, 111, '4.00', '0.00', 0, '6.00', '0.00', '24.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(13, 11, 107, '4.00', '0.00', 0, '13.00', '0.00', '52.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(14, 11, 118, '8.00', '0.00', 0, '9.00', '0.00', '72.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(15, 12, 115, '100.00', '0.00', 0, '120.00', '0.00', '12000.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(16, 13, 54, '3.00', '0.00', 3, '5001.00', '0.00', '15001.50', '0.00', 1, '2015-09-07', '2015-09-07'),
(17, 14, 110, '5.00', '0.00', 3, '4.00', '0.00', '20.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(18, 15, 110, '7.00', '0.00', 3, '7.00', '0.00', '49.00', '0.00', 1, '2015-09-07', '2015-09-07'),
(19, 16, 111, '150000.00', '0.00', 3, '1.00', '0.00', '75000.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(20, 17, 122, '4.00', '0.00', 3, '7.00', '0.00', '28.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(21, 18, 112, '4.00', '0.00', 3, '3.00', '0.00', '12.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(22, 18, 111, '4.00', '0.00', 3, '0.00', '0.00', '0.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(23, 19, 0, '10.00', '0.00', 0, '5.00', '0.00', '50.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(24, 20, 112, '5.00', '0.00', 3, '6.00', '0.00', '30.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(25, 21, 113, '6.00', '0.00', 3, '6.00', '0.00', '36.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(26, 22, 0, '4.00', '0.00', 0, '6.00', '0.00', '24.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(27, 23, 112, '4.00', '0.00', 3, '6.00', '0.00', '24.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(28, 24, 112, '6.00', '0.00', 3, '6.00', '0.00', '36.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(29, 25, 111, '6.00', '0.00', 3, '6.00', '0.00', '36.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(30, 26, 112, '5.00', '0.00', 3, '5.00', '0.00', '25.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(31, 27, 103, '3.00', '0.00', 3, '1.00', '0.00', '3.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(32, 28, 92, '5.00', '0.00', 3, '6.00', '0.00', '30.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(33, 29, 101, '5.00', '0.00', 3, '6.00', '0.00', '30.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(34, 30, 91, '7.00', '0.00', 3, '7.00', '0.00', '49.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(35, 30, 108, '6.00', '0.00', 3, '11.00', '0.00', '66.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(36, 31, 102, '8.00', '0.00', 3, '7.00', '0.00', '56.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(37, 31, 60, '7.00', '0.00', 3, '7.00', '0.00', '49.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(38, 31, 96, '5.00', '0.00', 3, '11.00', '0.00', '55.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(39, 32, 92, '125.00', '0.00', 3, '255.00', '0.00', '31817.50', '0.00', 1, '2015-09-08', '2015-09-08'),
(40, 32, 112, '100.00', '0.00', 3, '81.00', '0.00', '8085.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(41, 33, 91, '15.00', '0.00', 3, '20000.00', '0.00', '300000.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(42, 34, 91, '5.00', '0.00', 3, '100000.00', '0.00', '500000.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(43, 35, 90, '5.00', '0.00', 1, '10000.00', '0.00', '50000.00', '0.00', 1, '2015-09-08', '2015-09-08'),
(44, 36, 54, '4.00', '0.00', 3, '1000.00', '0.00', '4000.00', '0.00', 1, '2015-09-09', '2015-09-09'),
(45, 37, 54, '2.00', '0.00', 3, '2000.00', '0.00', '4000.00', '0.00', 1, '2015-09-09', '2015-09-09'),
(46, 38, 64, '1.00', '0.00', 3, '1111.00', '0.00', '1111.00', '0.00', 1, '2015-09-09', '2015-09-09'),
(47, 39, 90, '10.00', '0.00', 1, '100.00', '0.00', '1000.00', '0.00', 1, '2015-09-09', '2015-09-09'),
(48, 40, 111, '1500.00', '0.00', 3, '15.00', '0.00', '22500.00', '0.00', 1, '2015-09-09', '2015-09-09'),
(49, 41, 55, '1.00', '0.00', 3, '1000.00', '0.00', '1000.00', '0.00', 1, '2015-09-10', '2015-09-10'),
(50, 42, 90, '1.00', '0.00', 1, '100.00', '0.00', '100.00', '0.00', 1, '2015-09-10', '2015-09-10'),
(51, 43, 111, '2527.00', '0.00', 3, '149.00', '0.00', '376478.31', '0.00', 1, '2015-09-10', '2015-09-10'),
(52, 44, 90, '2526.70', '0.00', 1, '149.00', '0.00', '376478.31', '0.00', 1, '2015-09-10', '2015-09-10'),
(53, 45, 90, '140.85', '0.00', 1, '85.25', '0.00', '12007.63', '0.00', 1, '2015-09-10', '2015-09-10'),
(54, 46, 90, '1.00', '0.00', 1, '222.00', '0.00', '222.00', '0.00', 1, '2015-09-10', '2015-09-10'),
(55, 47, 113, '25.23', '0.00', 3, '777.81', '0.00', '19624.15', '0.00', 1, '2015-09-12', '2015-09-13'),
(56, 47, 106, '20.23', '0.00', 3, '111.12', '0.00', '2247.96', '0.00', 1, '2015-09-12', '2015-09-13'),
(57, 48, 94, '25.00', '0.00', 3, '250.75', '0.00', '6268.75', '0.00', 1, '2015-09-12', '2015-09-12'),
(58, 48, 110, '20.00', '0.00', 3, '150.50', '0.00', '3010.00', '0.00', 1, '2015-09-12', '2015-09-12'),
(59, 49, 90, '200.89', '0.00', 1, '150.00', '0.00', '30133.50', '0.00', 1, '2015-09-12', '2015-09-12'),
(60, 50, 90, '20.15', '0.00', 1, '150.00', '0.00', '3022.50', '0.00', 1, '2015-09-13', '2015-09-13'),
(61, 51, 90, '40.00', '0.00', 1, '2000.17', '0.00', '80006.80', '0.00', 1, '2015-09-13', '2015-09-13'),
(62, 52, 91, '150.00', '0.00', 3, '125.75', '0.00', '18862.50', '0.00', 1, '2015-09-13', '2015-09-13'),
(63, 52, 90, '105.80', '0.00', 1, '24.00', '0.00', '2539.20', '0.00', 1, '2015-09-13', '2015-09-13'),
(64, 53, 90, '1.00', '0.00', 1, '5000.00', '0.00', '5000.00', '0.00', 1, '2015-09-15', '2015-09-15'),
(65, 54, 90, '1.00', '0.00', 1, '5000.00', '0.00', '5000.00', '0.00', 1, '2015-09-15', '2015-09-15'),
(66, 55, 90, '15.00', '0.00', 1, '200.00', '0.00', '3000.00', '0.00', 1, '2015-09-16', '2015-09-16'),
(67, 56, 91, '6.00', '0.00', 3, '8000.00', '0.00', '48000.00', '0.00', 1, '2015-09-16', '2015-09-16'),
(68, 57, 90, '200.50', '0.00', 1, '250.00', '0.00', '50125.00', '0.00', 1, '2015-09-16', '2015-09-16'),
(69, 57, 111, '150.00', '0.00', 3, '180.00', '0.00', '27000.00', '0.00', 1, '2015-09-16', '2015-09-16'),
(70, 58, 90, '120.00', '0.00', 1, '24.00', '0.00', '2880.00', '0.00', 1, '2015-09-17', '2015-09-17'),
(71, 59, 90, '15.00', '0.00', 1, '2222.00', '0.00', '33330.00', '0.00', 1, '2015-09-20', '2015-09-20'),
(72, 60, 90, '10.00', '0.00', 1, '15000.00', '0.00', '150000.00', '0.00', 1, '2015-10-03', '2015-10-03'),
(73, 61, 90, '2.00', '0.00', 1, '1500.00', '0.00', '3000.00', '0.00', 1, '2015-10-17', '2015-10-17'),
(74, 62, 96, '1.00', '0.00', 3, '1000.00', '0.00', '1000.00', '0.00', 1, '2015-10-17', '2015-10-17'),
(75, 63, 92, '2.00', '0.00', 3, '7000.00', '0.00', '14000.00', '0.00', 1, '2015-10-21', '2015-10-21'),
(76, 64, 90, '200.70', '0.00', 1, '155.75', '0.00', '31259.03', '0.00', 1, '2015-10-21', '2015-10-21'),
(77, 65, 113, '2.00', '0.00', 3, '5600.75', '0.00', '11201.50', '0.00', 1, '2015-10-25', '2015-10-25'),
(78, 66, 103, '20.00', '0.00', 3, '900.00', '0.00', '18000.00', '0.00', 1, '2015-10-25', '2015-10-25'),
(79, 67, 112, '20.00', '0.00', 3, '600.00', '0.00', '12000.00', '0.00', 1, '2015-10-25', '2015-10-25'),
(80, 68, 113, '30.00', '0.00', 3, '700.00', '0.00', '21000.00', '0.00', 1, '2015-10-25', '2015-10-25'),
(81, 69, 95, '1003.00', '0.00', 3, '149.75', '0.00', '150199.25', '0.00', 1, '2015-10-25', '2015-10-25'),
(82, 70, 115, '6.00', '0.00', 3, '400.00', '0.00', '2400.00', '0.00', 1, '2015-10-25', '2015-10-25'),
(83, 71, 113, '50.00', '0.00', 3, '500.00', '0.00', '25000.00', '0.00', 1, '2015-10-25', '2015-10-25'),
(84, 72, 90, '100.00', '0.00', 1, '200.00', '0.00', '20000.00', '0.00', 1, '2015-10-25', '2015-10-25'),
(85, 73, 90, '100.00', '150.00', 1, '250.00', '250.00', '25000.00', '37500.00', 1, '2015-10-25', '2015-10-25'),
(86, 73, 111, '80.00', '70.00', 3, '400.00', '400.00', '32000.00', '28000.00', 1, '2015-10-25', '2015-10-25'),
(87, 74, 91, '150.00', '150.00', 0, '185.30', '185.30', '27795.00', '27795.00', 1, '2015-10-25', '2015-10-25'),
(88, 74, 95, '123.00', '123.00', 0, '0.25', '0.25', '30.75', '30.75', 1, '2015-10-25', '2015-10-25'),
(89, 75, 90, '151.50', '151.50', 1, '123.21', '123.21', '18666.32', '18666.32', 1, '2015-10-25', '2015-10-25'),
(90, 75, 115, '121.21', '121.21', 3, '152.00', '152.00', '18423.92', '18423.92', 1, '2015-10-25', '2015-10-25'),
(91, 76, 94, '40.00', '10.00', 3, '4000.00', '5000.00', '160000.00', '50000.00', 1, '2015-10-26', '2015-10-26'),
(92, 77, 122, '23.20', '20.00', 3, '6000.00', '6000.00', '139200.00', '120000.00', 1, '2015-10-26', '2015-10-26'),
(93, 78, 90, '150.00', '150.00', 1, '200.00', '250.00', '30000.00', '37500.00', 1, '2015-10-27', '2015-10-27'),
(94, 78, 112, '180.00', '180.00', 3, '220.00', '200.00', '39600.00', '36000.00', 1, '2015-10-27', '2015-10-27'),
(95, 79, 90, '10.00', '210.00', 1, '360.00', '360.00', '3600.00', '75600.00', 1, '2015-10-28', '2015-10-28'),
(96, 79, 91, '100.00', '280.00', 3, '240.00', '240.00', '24000.00', '67200.00', 1, '2015-10-28', '2015-10-28'),
(97, 80, 90, '120.00', '120.00', 1, '1241.00', '1241.00', '148920.00', '148920.00', 1, '2015-10-29', '2015-10-29'),
(98, 81, 90, '170.80', '170.80', 1, '600.50', '600.50', '102565.40', '102565.40', 1, '2015-10-29', '2015-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `salesdate` date DEFAULT NULL,
  `customerid` int(11) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `gamount` decimal(10,2) NOT NULL,
  `previousdue` decimal(10,2) NOT NULL DEFAULT '0.00',
  `presentbalance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `name`, `salesdate`, `customerid`, `discount`, `gamount`, `previousdue`, `presentbalance`, `status`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'I-11190', '2015-10-17', 30, '0.00', '30000.00', '-175790.00', '-145790.00', 1, 1, '2015-10-17', '2015-11-04'),
(2, 'I-36', '2015-10-17', 30, '0.00', '30000.00', '-145790.00', '-115790.00', 1, 1, '2015-10-17', '2015-11-04'),
(3, 'I-29375', '2015-10-17', 30, '0.00', '20000.00', '-115790.00', '-95790.00', 1, 1, '2015-10-17', '2015-11-04'),
(6, 'I-19514', '2015-09-01', 33, '0.00', '50000.00', '-165000.00', '-115000.00', 1, 1, '2015-09-01', '2015-11-04'),
(7, 'I-11334', '2015-09-15', 34, '0.00', '24000.00', '-165300.00', '-141300.00', 1, 1, '2015-09-15', '2015-11-01'),
(8, 'I-935', '2015-10-17', 33, '0.00', '65000.00', '-115000.00', '-50000.00', 1, 1, '2015-10-17', '2015-11-04'),
(9, 'I-13720', '2015-10-21', 1, '0.00', '0.00', '-12729031.00', '-12729031.00', 1, 1, '2015-10-21', '2015-11-02'),
(10, 'I-9644', '2015-10-28', 2, '2500.00', '20700.00', '2518.00', '23218.00', 1, 1, '2015-10-28', '2015-10-28'),
(11, 'I-10318', '2015-10-28', 1, '0.00', '10000.00', '-12729031.00', '-12719031.00', 1, 1, '2015-10-28', '2015-11-02'),
(19, 'I-8310', '2015-10-28', 1, '0.00', '39000.00', '-12719031.00', '-12680031.00', 1, 1, '2015-10-28', '2015-11-02'),
(21, 'I-26525', '2015-10-29', 37, '500.00', '9000.00', '20000.00', '29000.00', 1, 1, '2015-10-29', '2015-10-29'),
(24, 'I-2972', '2015-10-29', 38, '500.00', '60000.00', '-448000.00', '-388000.00', 1, 1, '2015-10-29', '2015-10-29'),
(25, 'I-3749', '2015-10-29', 38, '500.00', '4000.00', '-4000.00', '0.00', 1, 1, '2015-10-29', '2015-10-29'),
(26, 'I-3252', '2015-10-29', 38, '1000.00', '38000.00', '-369000.00', '-331000.00', 1, 1, '2015-10-29', '2015-10-29'),
(27, 'I-18677', '2015-10-29', 1, '500.00', '500.00', '-12680031.00', '-12679531.00', 1, 1, '2015-10-29', '2015-11-02'),
(29, 'I-21283', '2015-10-29', 38, '50000.00', '100000.00', '-331000.00', '-231000.00', 1, 1, '2015-10-29', '2015-10-29'),
(30, 'I-26724', '2015-10-29', 37, '0.00', '29998.00', '20000.00', '49998.00', 1, 1, '2015-10-29', '2015-10-29'),
(31, 'I-32383', '2015-10-29', 38, '0.00', '47000.00', '-231000.00', '-184000.00', 1, 1, '2015-10-29', '2015-10-29'),
(32, 'I-24820', '2015-10-29', 38, '0.00', '50000.00', '-50000.00', '0.00', 1, 1, '2015-10-29', '2015-10-29'),
(33, 'I-9624', '2015-10-29', 38, '1000.00', '59000.00', '-59000.00', '0.00', 1, 1, '2015-10-29', '2015-10-29'),
(34, 'I-21620', '2015-10-29', 38, '0.00', '45000.00', '-45000.00', '0.00', 1, 1, '2015-10-29', '2015-10-29'),
(35, 'I-32591', '2015-10-29', 38, '0.00', '80000.00', '0.00', '80000.00', 1, 1, '2015-10-29', '2015-10-29'),
(36, 'I-2614', '2015-10-29', 1, '1500.00', '53600.00', '-82000.00', '-28400.00', 1, 1, '2015-10-29', '2015-11-02'),
(37, 'I-17060', '2015-10-29', 41, '750.00', '45750.00', '-45750.00', '0.00', 1, 1, '2015-10-29', '2015-10-29'),
(38, 'I-20780', '2015-10-31', 1, '0.00', '12000.00', '53600.00', '65600.00', 0, 1, '2015-10-31', '2015-10-31'),
(40, 'I-25998', '2015-11-01', 2, '500.00', '50000.00', '29518.00', '79518.00', 1, 1, '2015-11-01', '2015-11-01'),
(41, 'I-7310', '2015-11-01', 6, '90.00', '156550.00', '100000.00', '256550.00', 1, 1, '2015-11-01', '2015-11-01'),
(42, 'I-28588', '2015-11-03', 1, '0.00', '20000.00', '-28400.00', '-8400.00', 1, 1, '2015-11-03', '2015-11-02'),
(44, 'I-11358', '2015-11-04', 1, '0.00', '15000.00', '-7400.00', '7600.00', 1, 1, '2015-11-04', '2015-11-04'),
(45, 'I-31083', '2015-11-04', 1, '0.00', '10000.00', '-8400.00', '1600.00', 1, 1, '2015-11-04', '2015-11-04'),
(46, 'I-4678', '2015-11-04', 2, '0.00', '1200.00', '2518.00', '3718.00', 0, 1, '2015-11-04', '2015-11-04'),
(47, 'I-8060', '2015-11-04', 1, '5000.00', '25000.00', '1600.00', '26600.00', 1, 1, '2015-11-04', '2015-11-04');

-- --------------------------------------------------------

--
-- Table structure for table `salesdetails`
--

CREATE TABLE IF NOT EXISTS `salesdetails` (
`id` int(11) NOT NULL,
  `salesid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `mesid` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `salesdetails`
--

INSERT INTO `salesdetails` (`id`, `salesid`, `itemid`, `quantity`, `mesid`, `rate`, `amount`, `userid`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2.00', 3, '15000.00', '30000.00', 1, '2015-10-17', '2015-10-17'),
(2, 2, 2, '2.00', 3, '15000.00', '30000.00', 1, '2015-10-17', '2015-10-17'),
(3, 3, 2, '2.00', 3, '10000.00', '20000.00', 1, '2015-10-17', '2015-10-17'),
(4, 4, 1, '2.00', 3, '5000.00', '10000.00', 1, '2015-09-01', '2015-09-01'),
(5, 5, 1, '2.00', 3, '11000.00', '22000.00', 1, '2015-09-15', '2015-09-15'),
(6, 6, 1, '2.00', 3, '25000.00', '50000.00', 1, '2015-09-01', '2015-09-01'),
(7, 7, 2, '2.00', 3, '12000.00', '24000.00', 1, '2015-09-15', '2015-09-15'),
(8, 8, 1, '3.00', 3, '15000.00', '45000.00', 1, '2015-10-17', '2015-10-17'),
(9, 8, 2, '1.00', 3, '20000.00', '20000.00', 1, '2015-10-17', '2015-10-17'),
(10, 9, 80, '0.00', 3, '7000.00', '0.00', 1, '2015-10-21', '2015-10-21'),
(11, 10, 1, '4.00', 3, '5800.00', '23200.00', 1, '2015-10-28', '2015-10-28'),
(12, 11, 2, '4.00', 3, '2500.00', '10000.00', 1, '2015-10-28', '2015-10-28'),
(13, 12, 131, '130.00', 7, '2500.00', '325000.00', 1, '2015-10-28', '2015-10-28'),
(14, 13, 131, '20.00', 7, '1500.00', '30000.00', 1, '2015-10-28', '2015-10-28'),
(16, 15, 1, '2.00', 3, '2000.00', '4000.00', 1, '2015-10-28', '2015-10-28'),
(19, 18, 32, '15.00', 3, '1000.00', '15000.00', 1, '2015-10-28', '2015-10-28'),
(20, 19, 1, '2.00', 3, '12000.00', '24000.00', 1, '2015-10-28', '2015-10-28'),
(21, 19, 2, '3.00', 3, '5000.00', '15000.00', 1, '2015-10-28', '2015-10-28'),
(22, 20, 30, '20.00', 3, '1000.00', '20000.00', 1, '2015-10-28', '2015-10-28'),
(23, 21, 1, '2.00', 3, '1250.00', '2500.00', 1, '2015-10-29', '2015-10-29'),
(24, 21, 2, '2.00', 3, '2500.00', '5000.00', 1, '2015-10-29', '2015-10-29'),
(25, 21, 30, '10.00', 3, '200.00', '2000.00', 1, '2015-10-29', '2015-10-29'),
(26, 23, 1, '2.00', 3, '1400.00', '2800.00', 1, '2015-10-29', '2015-10-29'),
(27, 24, 2, '2.00', 3, '15250.00', '30500.00', 1, '2015-10-29', '2015-10-29'),
(28, 24, 30, '15.00', 3, '2000.00', '30000.00', 1, '2015-10-29', '2015-10-29'),
(29, 25, 2, '3.00', 3, '1500.00', '4500.00', 1, '2015-10-29', '2015-10-29'),
(30, 26, 1, '2.00', 3, '12000.00', '24000.00', 1, '2015-10-29', '2015-10-29'),
(31, 26, 30, '100.00', 3, '150.00', '15000.00', 1, '2015-10-29', '2015-10-29'),
(32, 27, 1, '1.00', 3, '1000.00', '1000.00', 1, '2015-10-29', '2015-10-29'),
(35, 29, 31, '100.00', 3, '1500.00', '150000.00', 1, '2015-10-29', '2015-10-29'),
(36, 30, 1, '2.00', 3, '14999.00', '29998.00', 1, '2015-10-29', '2015-10-29'),
(37, 31, 7, '2.00', 3, '23500.00', '47000.00', 1, '2015-10-29', '2015-10-29'),
(38, 32, 31, '4.00', 3, '12500.00', '50000.00', 1, '2015-10-29', '2015-10-29'),
(39, 33, 2, '2.00', 3, '15000.00', '30000.00', 1, '2015-10-29', '2015-10-29'),
(40, 33, 32, '20.00', 3, '1500.00', '30000.00', 1, '2015-10-29', '2015-10-29'),
(41, 34, 2, '3.00', 3, '15000.00', '45000.00', 1, '2015-10-29', '2015-10-29'),
(42, 35, 31, '100.00', 3, '800.00', '80000.00', 1, '2015-10-29', '2015-10-29'),
(43, 36, 1, '5.00', 0, '4500.00', '22500.00', 1, '2015-10-29', '2015-10-29'),
(44, 36, 4, '4.00', 3, '8150.00', '32600.00', 1, '2015-10-29', '2015-10-29'),
(45, 37, 1, '2.00', 3, '4500.00', '9000.00', 1, '2015-10-29', '2015-10-29'),
(46, 37, 31, '50.00', 3, '750.00', '37500.00', 1, '2015-10-29', '2015-10-29'),
(47, 38, 1, '2.00', 3, '6000.00', '12000.00', 1, '2015-10-31', '2015-10-31'),
(50, 40, 1, '4.00', 3, '4500.00', '18000.00', 1, '2015-11-01', '2015-11-01'),
(51, 40, 7, '5.00', 3, '6500.00', '32500.00', 1, '2015-11-01', '2015-11-01'),
(52, 41, 2, '2.00', 3, '78000.00', '156000.00', 1, '2015-11-01', '2015-11-01'),
(53, 41, 4, '1.00', 3, '640.00', '640.00', 1, '2015-11-01', '2015-11-01'),
(54, 42, 1, '1.00', 3, '20000.00', '20000.00', 1, '2015-11-03', '2015-11-03'),
(56, 44, 1, '2.00', 3, '7500.00', '15000.00', 1, '2015-11-04', '2015-11-04'),
(57, 45, 31, '2.00', 3, '5000.00', '10000.00', 1, '2015-11-04', '2015-11-04'),
(58, 46, 30, '3.00', 3, '400.00', '1200.00', 1, '2015-11-04', '2015-11-04'),
(59, 47, 1, '2.00', 3, '15000.00', '30000.00', 1, '2015-11-04', '2015-11-04');

-- --------------------------------------------------------

--
-- Table structure for table `submenus`
--

CREATE TABLE IF NOT EXISTS `submenus` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `link` text COLLATE utf32_bin NOT NULL,
  `status` int(10) NOT NULL,
  `menuid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `submenus`
--

INSERT INTO `submenus` (`id`, `name`, `link`, `status`, `menuid`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'Users Group', 'usersgroup', 1, 1, 1, '2015-07-02', NULL),
(2, 'Users', 'users', 1, 1, 1, '2015-07-15', NULL),
(3, 'Users Permission', 'userspermission', 1, 1, 1, '2015-07-15', NULL),
(4, 'Item Group', 'itemgroup', 1, 2, 1, '2015-07-01', NULL),
(5, 'Item Category', 'itemsubgroup', 1, 2, 1, '2015-07-15', NULL),
(6, 'Item Model', 'itemmaster', 1, 2, 1, '2015-07-08', NULL),
(7, 'Measurement Group', 'measurementgroup', 1, 2, 1, '2015-07-16', NULL),
(8, 'Measurement Unit', 'measurementunit', 1, 2, 1, '2015-07-15', NULL),
(9, 'Vendor/Supplier', 'suppliers', 1, 1, 1, '2015-07-09', NULL),
(10, 'Customer/Client', 'customers', 1, 1, 1, '2015-07-15', NULL),
(11, 'Purchase Order', 'purchase', 1, 3, 1, '2015-07-15', NULL),
(12, 'Bills Pay', 'billspay', 1, 0, 1, '2015-07-22', NULL),
(13, 'Physical Sales', 'physicalsales', 1, 3, 1, '2015-07-16', NULL),
(14, 'Bills Receives', 'billsreceives', 1, 0, 1, '2015-07-22', NULL),
(15, 'Reports on Ledger', 'generalledger', 1, 8, 1, '2015-07-15', NULL),
(16, 'Report On Purchases', 'reportpurchases', 1, 3, 1, '2015-07-14', NULL),
(17, 'Reports on Sales', 'reportssale', 1, 3, 1, '2015-07-15', NULL),
(18, 'Stock In Analyst', 'stockin', 1, 0, 1, '2015-07-15', NULL),
(19, 'Stock Out Analyst', 'stockout', 1, 0, 1, '2015-07-15', NULL),
(20, 'Accounts Payable', 'accountspayable', 1, 0, 1, '2015-07-15', NULL),
(21, 'Accounts Receivels', 'accountreceives', 1, 0, 1, '2015-07-15', NULL),
(22, 'Loss & profit Analys', 'reportlossprofit', 1, 5, 1, '2015-07-15', NULL),
(23, 'Stock In Out Analys', 'stokrinout', 1, 5, 1, '2015-07-14', NULL),
(24, 'Bank Book', 'bankbook', 1, 4, 1, '2015-07-15', NULL),
(25, 'Trial Balance', 'trialbalance', 1, 4, 1, '2015-07-15', NULL),
(26, 'Factory Item Master', 'factoryitem', 1, 6, 1, '2015-07-02', NULL),
(27, 'Stock In Out Analyst', 'fstokin', 1, 6, 1, '2015-07-15', NULL),
(28, 'Stock out analyst', 'stockout', 1, 0, 1, '2015-07-14', NULL),
(29, 'Employee Info', 'employee', 1, 7, 1, '2015-07-15', NULL),
(30, 'Employee Salary', 'employeesal', 1, 7, 1, '2015-07-20', NULL),
(31, 'Performance appraisal', 'performance', 1, 0, 1, '2015-07-22', NULL),
(32, 'Benefits administration', 'benefit', 1, 7, 1, '2015-07-22', NULL),
(33, 'Absence management', 'absense', 1, 0, 1, '2015-07-08', NULL),
(34, 'Reports Analyst', 'reports', 1, 7, 1, '2015-07-14', NULL),
(35, 'Bank Information', 'bankinfo', 0, 1, 1, '2015-08-11', NULL),
(36, 'Bank Account', 'bankaccount', 0, 1, 1, '2015-08-11', NULL),
(37, 'PL Account', 'placcount', 1, 4, 1, '2015-09-14', NULL),
(38, 'Balance Sheet', 'balancesheet', 0, 4, 1, '2015-09-14', NULL),
(39, 'Contra Voucher Entry', 'contravoucher', 1, 9, 1, '2015-09-30', NULL),
(40, 'Journal Entry', 'ledgerentry', 1, 8, 1, '2015-09-03', NULL),
(41, 'Create Ledger ', 'coa', 0, 8, 1, '2015-08-11', NULL),
(42, 'Voucher Entry', 'voucher', 1, 9, 1, '2015-08-04', NULL),
(43, 'Cash Book', 'cashbook', 1, 4, 1, '2015-10-20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
`id` int(11) NOT NULL,
  `code` text COLLATE utf32_bin NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `openbalance` decimal(10,2) NOT NULL,
  `preaddress` text COLLATE utf32_bin NOT NULL,
  `peraddress` text COLLATE utf32_bin NOT NULL,
  `phone` text COLLATE utf32_bin NOT NULL,
  `email` text COLLATE utf32_bin NOT NULL,
  `fax` text COLLATE utf32_bin NOT NULL,
  `url` text COLLATE utf32_bin NOT NULL,
  `coastatus` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `code`, `name`, `openbalance`, `preaddress`, `peraddress`, `phone`, `email`, `fax`, `url`, `coastatus`, `userid`, `created_at`, `updated_at`) VALUES
(2, 's-102', 'Asha Trading', '100000.00', 'Gulshan', 'Cox''s Bazar', '654321', 'iqbal@gmail.com', 'kkkk', 'http://iqbal', 1, 1, '2015-08-01', '2015-09-21'),
(4, 's-0244744', 'S A Corporation', '0.00', 'address', 'sdsdsd', '0760-284-3360', 'sa_sarker11@yahoo.com', 'ss', '', 1, 1, '2015-08-13', '2015-10-21'),
(6, 'S-22618', 'Jharna Chemical Supply', '0.00', 'er', 'etr', '34', 'a@yahoo.com', 'tyu', 'http://co', 0, 1, '2015-08-16', '2015-08-27'),
(7, 'S-10176', 'ABC Enterprise', '0.00', 'Dhaka', 'Dhaka', '3245631313', '', '', '', 0, 1, '2015-08-19', '2015-08-27'),
(8, 'S-18808', 'Babul & Sons', '0.00', 'Dhaka', 'Gajipur', '0760-284-3360', 'sascafs@outlook.com', 'aaa', '', 0, 1, '2015-08-20', '2015-08-27'),
(9, 'S-12029', 'Nasir Block', '150000.00', 'Dhaka', 'Dhaka', '154444444', 'sascafs@outlook.com', '', '', 0, 1, '2015-08-27', '2015-08-30'),
(11, 'S-22856', 'Barisal Battery', '100000.00', 'Barisal', 'comilla', '1111-111-1111', 'sascafs@outlook.com', '', 'http://www.sascafs.com', 1, 1, '2015-08-30', '2015-09-21'),
(12, 'S-1286', 'LIC bangladseh', '150000.00', 'Basabo', 'Basabo', '1111-111-1111', '', '', '', 0, 1, '2015-08-30', '2015-08-31'),
(13, 'S-1788', 'kkk', '777.00', 'kkkk', 'jjjj', '888', 'kkkk@gmail.com', '', '', 0, 1, '2015-08-30', '2015-08-30'),
(14, 'S-20897', 'Bangla Solar', '500000.00', 'Gulistan', 'Gulistan', '0760-284-3360', 'sascafs@outlook.com', 'hghghg', 'http://www.sascafs.com', 0, 1, '2015-08-30', '2015-09-12'),
(16, 'S-29342', 'Tumon', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-07', '2015-09-07'),
(18, 'S-3207', 'FS', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-07', '2015-09-13'),
(20, 'S-5117', 'Roman', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-07', '2015-09-07'),
(21, 'S-574', 'WOW', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-07', '2015-09-07'),
(22, 'S-7984', 'subrata', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-07', '2015-09-07'),
(23, 'S-32539', 'ioo', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-07', '2015-09-07'),
(24, 'S-26445', '', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-08', '2015-09-08'),
(25, 'S-18195', 'ttttttt', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-08', '2015-09-08'),
(26, 'S-16454', 'FS CO', '58500.00', 'Dhaka, BD', 'Dhaka,BD', '0178454545', 'sascafs@outlook.com', '', '', 0, 1, '2015-09-08', '2015-09-08'),
(27, 'S-25945', 'Binimoy Battery', '125000.00', 'Dhaka', 'Dhaka', '0760-284-3360', '', '', '', 0, 1, '2015-09-08', '2015-09-08'),
(28, 'S-30272', 'yyy', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-09', '2015-09-09'),
(29, 'S-19305', '', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-09', '2015-09-09'),
(30, 'S-1359', 'kk', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-09', '2015-09-09'),
(31, 'S-30321', 'test7', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-09', '2015-09-09'),
(32, 'S-16411', 'Emon', '0.00', '', '', '', '', '', '', 0, 0, '2015-09-09', '2015-09-09'),
(33, 'S-19586', 'ASDF', '25000.00', 'Banani', 'Banani', '01711-285246', 'asdf@gmail.com', '', '', 0, 1, '2015-09-12', '2015-09-12'),
(34, 'S-7096', 'SAA', '12500.00', 'Dhaka', 'Badda Link road', '01971000200', '', '', '', 0, 1, '2015-09-13', '2015-09-13'),
(35, 'S-3809', 'ty', '243432.00', 'dr', '', '34324234', '', '', '', 0, 1, '2015-09-13', '2015-09-13'),
(36, 'S-17726', 'asd', '0.00', 'Dhanmondi', '', '', '', '', '', 0, 0, '2015-11-01', '2015-11-01');

-- --------------------------------------------------------

--
-- Table structure for table `suppliersledger`
--

CREATE TABLE IF NOT EXISTS `suppliersledger` (
`id` int(11) NOT NULL,
  `pav` int(11) NOT NULL,
  `puv` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suppliersledger`
--

INSERT INTO `suppliersledger` (`id`, `pav`, `puv`, `sid`, `amount`, `created_at`, `updated_at`) VALUES
(1, 0, 33, 4, '299667.00', '2015-09-08', '2015-09-08'),
(2, 0, 34, 2, '500500.00', '2015-09-08', '2015-09-08'),
(3, 43, 0, 2, '3000.00', '2015-09-08', '2015-09-08'),
(4, 0, 35, 27, '47000.00', '2015-09-08', '2015-09-08'),
(5, 44, 0, 27, '10000.00', '2015-09-08', '2015-09-08'),
(6, 0, 36, 2, '4000.00', '2015-09-09', '2015-09-09'),
(7, 0, 37, 2, '4000.00', '2015-09-09', '2015-09-09'),
(8, 50, 0, 2, '70000.00', '2015-09-09', '2015-09-09'),
(9, 0, 38, 2, '1111.00', '2015-09-09', '2015-09-09'),
(10, 0, 39, 2, '990.00', '2015-09-09', '2015-09-09'),
(11, 0, 40, 6, '22350.00', '2015-09-09', '2015-09-09'),
(12, 0, 41, 2, '2000.00', '2015-09-10', '2015-09-10'),
(13, 0, 42, 2, '3000.00', '2015-09-10', '2015-09-10'),
(14, 0, 43, 2, '376478.30', '2015-09-10', '2015-09-10'),
(15, 0, 44, 2, '376478.30', '2015-09-10', '2015-09-10'),
(16, 0, 45, 2, '10707.63', '2015-09-10', '2015-09-10'),
(17, 0, 46, 2, '222.00', '2015-09-10', '2015-09-10'),
(18, 0, 47, 4, '26900.00', '2015-09-12', '2015-09-12'),
(19, 0, 48, 33, '10278.75', '2015-09-12', '2015-09-12'),
(20, 0, 49, 8, '30000.00', '2015-09-12', '2015-09-12'),
(21, 0, 50, 2, '3122.00', '2015-09-13', '2015-09-13'),
(22, 56, 0, 2, '5000.00', '2015-09-13', '2015-09-13'),
(23, 0, 51, 2, '81906.00', '2015-09-13', '2015-09-13'),
(24, 0, 52, 34, '22751.70', '2015-09-13', '2015-09-13'),
(25, 68, 0, 2, '5520.00', '2015-09-14', '2015-09-14'),
(26, 69, 0, 4, '15000.00', '2015-09-14', '2015-09-14'),
(27, 0, 53, 2, '5000.00', '2015-09-15', '2015-09-15'),
(28, 0, 54, 2, '5000.00', '2015-09-15', '2015-09-15'),
(29, 0, 55, 11, '2000.00', '2015-09-16', '2015-09-16'),
(30, 74, 0, 11, '200.00', '2015-09-16', '2015-09-16'),
(31, 82, 0, -1, '565.00', '2015-09-16', '2015-09-16'),
(32, 83, 0, 11, '50000.00', '2015-09-16', '2015-09-16'),
(33, 110, 0, -1, '1212.00', '2015-09-16', '2015-09-16'),
(34, 111, 0, -1, '5000.00', '2015-09-16', '2015-09-16'),
(35, 112, 0, 11, '5000.00', '2015-09-16', '2015-09-16'),
(36, 113, 0, 11, '1.00', '2015-09-16', '2015-09-16'),
(37, 114, 0, 11, '100.00', '2015-09-16', '2015-09-16'),
(38, 0, 56, 9, '48420.00', '2015-09-16', '2015-09-16'),
(39, 0, 57, 4, '78125.00', '2015-09-16', '2015-09-16'),
(40, 115, 0, 11, '5000.00', '2015-09-17', '2015-09-17'),
(41, 117, 0, 11, '9999.00', '2015-09-17', '2015-09-17'),
(42, 118, 0, 11, '9999.00', '2015-09-17', '2015-09-17'),
(43, 121, 0, 11, '23.00', '2015-09-17', '2015-09-17'),
(44, 122, 0, 11, '444.00', '2015-09-17', '2015-09-17'),
(45, 127, 0, 11, '444.00', '2015-09-17', '2015-09-17'),
(46, 128, 0, 11, '21.00', '2015-09-17', '2015-09-17'),
(47, 130, 0, 11, '9999.00', '2015-09-17', '2015-09-17'),
(48, 131, 0, 11, '21.00', '2015-09-17', '2015-09-17'),
(49, 143, 0, 11, '88888.00', '2015-09-17', '2015-09-17'),
(50, 0, 58, 2, '2580.00', '2015-09-17', '2015-09-17'),
(51, 146, 0, 11, '10000.00', '2015-09-01', '2015-09-01'),
(52, 150, 0, 11, '10000.00', '2015-09-08', '2015-09-08'),
(53, 152, 0, 11, '5000.00', '2015-09-20', '2015-09-20'),
(54, 153, 0, 11, '2000.00', '2015-09-20', '2015-09-20'),
(55, 0, 59, 2, '33330.00', '2015-09-20', '2015-09-20'),
(56, 166, 0, 11, '34000.00', '2015-09-21', '2015-09-21'),
(57, 7, 0, 11, '100.00', '2015-09-21', '2015-09-21'),
(58, 8, 0, 11, '5000.00', '2015-09-06', '2015-09-06'),
(59, 9, 0, 11, '100000.00', '2015-09-21', '2015-09-21'),
(60, 10, 0, 11, '1500.00', '2015-09-21', '2015-09-21'),
(61, 11, 0, 11, '65000.00', '2015-09-21', '2015-09-21'),
(62, 15, 0, 2, '5000.00', '2015-09-21', '2015-09-21'),
(63, 21, 0, 11, '12000.00', '2015-09-21', '2015-09-21'),
(64, 22, 0, 2, '2000.00', '2015-09-21', '2015-09-21'),
(65, 23, 0, 2, '20000.00', '2015-09-21', '2015-09-21'),
(66, 25, 0, 2, '20000.00', '2015-09-21', '2015-09-21'),
(67, 26, 0, 2, '20000.00', '2015-09-21', '2015-09-21'),
(68, 27, 0, 11, '10000.00', '2015-09-21', '2015-09-21'),
(69, 29, 0, 11, '21.00', '2015-09-21', '2015-09-21'),
(70, 30, 0, 11, '777.00', '2015-09-21', '2015-09-21'),
(71, 31, 0, 11, '600.00', '2015-09-21', '2015-09-21'),
(72, 32, 0, 11, '10000.00', '2015-09-21', '2015-09-21'),
(73, 0, 60, 2, '150022.00', '2015-10-03', '2015-10-03'),
(74, 36, 0, 2, '90.00', '2015-10-11', '2015-10-11'),
(75, 38, 0, 2, '100000.00', '2015-10-11', '2015-10-11'),
(76, 39, 0, 11, '349000.00', '2015-10-14', '2015-10-14'),
(77, 0, 61, 4, '3000.00', '2015-10-17', '2015-10-17'),
(78, 0, 62, 4, '1000.00', '2015-10-17', '2015-10-17'),
(79, 0, 63, 7, '14000.00', '2015-10-21', '2015-10-21'),
(80, 0, 64, 4, '31500.00', '2015-10-21', '2015-10-21'),
(81, 7, 0, 4, '30000.00', '2015-10-21', '2015-10-21'),
(82, 11, 0, 2, '7250.00', '2015-10-21', '2015-10-21'),
(83, 15, 0, 2, '3500.00', '2015-10-21', '2015-10-21'),
(84, 0, 65, 7, '11400.00', '2015-10-25', '2015-10-25'),
(85, 0, 66, 8, '18010.00', '2015-10-25', '2015-10-25'),
(86, 0, 67, 8, '12000.00', '2015-10-25', '2015-10-25'),
(87, 0, 68, 6, '20997.00', '2015-10-25', '2015-10-25'),
(88, 29, 0, 2, '1500.00', '2015-10-25', '2015-10-25'),
(89, 0, 69, 9, '150249.00', '2015-10-25', '2015-10-25'),
(90, 0, 70, 9, '2400.00', '2015-10-25', '2015-10-25'),
(91, 0, 71, 6, '25000.00', '2015-10-25', '2015-10-25'),
(92, 0, 72, 7, '20000.00', '2015-10-25', '2015-10-25'),
(93, 0, 73, 4, '57000.00', '2015-10-25', '2015-10-25'),
(94, 0, 74, 6, '27961.09', '2015-10-25', '2015-10-25'),
(95, 0, 75, 4, '37090.24', '2015-10-25', '2015-10-25'),
(96, 0, 76, 6, '160043.14', '2015-10-26', '2015-10-26'),
(97, 0, 77, 8, '139167.09', '2015-10-26', '2015-10-26'),
(98, 38, 0, 2, '4500.00', '2015-10-26', '2015-10-26'),
(99, 1, 0, 4, '7500.00', '2015-10-26', '2015-10-26'),
(100, 9, 0, 11, '2500.00', '2015-10-26', '2015-10-26'),
(101, 0, 78, 2, '71100.00', '2015-10-27', '2015-10-27'),
(102, 13, 0, 4, '32000.00', '2015-10-27', '2015-10-27'),
(103, 14, 0, 2, '7500.00', '2015-10-27', '2015-10-27'),
(104, 20, 0, 4, '6300.00', '2015-10-27', '2015-10-27'),
(105, 23, 0, 4, '8250.00', '2015-10-27', '2015-10-27'),
(106, 29, 0, 4, '5600.00', '2015-10-27', '2015-10-27'),
(107, 41, 0, 4, '59000.00', '2015-10-28', '2015-10-28'),
(108, 42, 0, 11, '59000.00', '2015-10-28', '2015-10-28'),
(109, 0, 79, 4, '30100.00', '2015-10-28', '2015-10-28'),
(110, 0, 80, 26, '150000.00', '2015-10-29', '2015-10-29'),
(111, 0, 81, 4, '103000.00', '2015-10-29', '2015-10-29'),
(112, 61, 0, 11, '12000.00', '2015-11-01', '2015-11-01'),
(113, 62, 0, 2, '34000.00', '2015-11-01', '2015-11-01');

-- --------------------------------------------------------

--
-- Table structure for table `taxrate`
--

CREATE TABLE IF NOT EXISTS `taxrate` (
`id` int(11) NOT NULL,
  `name` text NOT NULL,
  `percentage` float NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taxrate`
--

INSERT INTO `taxrate` (`id`, `name`, `percentage`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'Tax Rate1', 10, 1, '2015-08-12 08:08:45', '0000-00-00 00:00:00'),
(2, 'Tax Rate2', 20, 1, '2015-08-12 08:09:20', '0000-00-00 00:00:00'),
(3, 'Tax Rate3', 30, 1, '2015-08-12 08:09:39', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `usergroups`
--

CREATE TABLE IF NOT EXISTS `usergroups` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `usergroups`
--

INSERT INTO `usergroups` (`id`, `name`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 1, '2015-09-22', '2015-09-22'),
(2, 'Admin', 2, '2015-09-22', '2015-09-22'),
(3, 'Basic Users', 1, '2015-09-22', '2015-09-22'),
(4, 'Branch Users', 1, '2015-09-22', '2015-09-22'),
(5, 'Warehouse   Users', 1, '2015-09-22', '2015-09-22'),
(6, 'aaa', 1, '2015-10-17', '2015-10-17'),
(7, 'aaa', 1, '2015-10-17', '2015-10-17'),
(8, 'aaa', 1, '2015-10-17', '2015-10-17'),
(9, 'bb', 1, '2015-10-17', '2015-10-17'),
(10, 'bb', 1, '2015-10-17', '2015-10-17'),
(11, 'bb', 1, '2015-10-17', '2015-10-17'),
(12, 'bb', 1, '2015-10-17', '2015-10-17'),
(13, 'bb', 1, '2015-10-17', '2015-10-17'),
(14, 'bb', 1, '2015-10-17', '2015-10-17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `name` text COLLATE utf32_bin NOT NULL,
  `email` text COLLATE utf32_bin NOT NULL,
  `password` text COLLATE utf32_bin NOT NULL,
  `remember_token` text COLLATE utf32_bin NOT NULL,
  `usergroupid` int(11) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `usergroupid`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'info@jcobattery.com', '$2y$10$JtnR9r/0/C9BNjL6zYHUaOG3Y.iW5R7FrPzYsXabfVS7/0HKBmBci', 'cIFtPuBBSbTT06J34FuOOpLV6Rflw9LUPvxvrqWWo72pXUVlbczrISq6YBBl', 1, '2015-09-02', '2015-11-04'),
(2, 'Bscic', 'bscic@jcobattery.com', '$2y$10$D7cscRHZ9kwaR0C8z1957OENeOgvf7mcsfFsQsDPMRnxnXorzWZI2', 'oo4NVlEG8HevRlfUgUmjaoLw9NEiKaLxD3Xe0qWzskdwfljuKZfAHj4RoqPE', 5, '2015-12-31', '2015-12-31'),
(3, 'Tatibazaar', 'tatibazaar@jcobattery.com', '$2y$10$uPNMCRzstUX89Sx60iTJq.Qn8hNjr53fXOy4dFRULuGf68pQnK1Ju', 'DbgQjqfUnRPve9ss9TnRaZYkNDkM1QiMZTsq3A8mKq7JAeSCX89jMUTVosvd', 5, '2015-12-31', '2015-11-02');

-- --------------------------------------------------------

--
-- Table structure for table `userspermission`
--

CREATE TABLE IF NOT EXISTS `userspermission` (
`id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `submenuid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1242 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `userspermission`
--

INSERT INTO `userspermission` (`id`, `userid`, `submenuid`, `status`, `created_at`, `updated_at`) VALUES
(615, 14, 1, 1, '2015-09-02', '2015-09-02'),
(616, 14, 2, 1, '2015-09-02', '2015-09-02'),
(617, 14, 3, 1, '2015-09-02', '2015-09-02'),
(618, 14, 4, 1, '2015-09-02', '2015-09-02'),
(619, 14, 5, 1, '2015-09-02', '2015-09-02'),
(620, 14, 6, 1, '2015-09-02', '2015-09-02'),
(621, 14, 7, 1, '2015-09-02', '2015-09-02'),
(622, 14, 8, 1, '2015-09-02', '2015-09-02'),
(623, 14, 9, 1, '2015-09-02', '2015-09-02'),
(624, 14, 10, 1, '2015-09-02', '2015-09-02'),
(625, 14, 11, 1, '2015-09-02', '2015-09-02'),
(626, 14, 13, 1, '2015-09-02', '2015-09-02'),
(627, 14, 15, 1, '2015-09-02', '2015-09-02'),
(628, 14, 16, 1, '2015-09-02', '2015-09-02'),
(629, 14, 17, 1, '2015-09-02', '2015-09-02'),
(630, 14, 22, 1, '2015-09-02', '2015-09-02'),
(631, 14, 23, 1, '2015-09-02', '2015-09-02'),
(632, 14, 24, 1, '2015-09-02', '2015-09-02'),
(633, 14, 25, 1, '2015-09-02', '2015-09-02'),
(634, 14, 26, 1, '2015-09-02', '2015-09-02'),
(635, 14, 27, 1, '2015-09-02', '2015-09-02'),
(636, 14, 29, 1, '2015-09-02', '2015-09-02'),
(637, 14, 30, 1, '2015-09-02', '2015-09-02'),
(638, 14, 31, 1, '2015-09-02', '2015-09-02'),
(639, 14, 32, 1, '2015-09-02', '2015-09-02'),
(640, 14, 33, 1, '2015-09-02', '2015-09-02'),
(641, 14, 34, 1, '2015-09-02', '2015-09-02'),
(642, 14, 35, 1, '2015-09-02', '2015-09-02'),
(643, 14, 36, 1, '2015-09-02', '2015-09-02'),
(644, 14, 37, 1, '2015-09-02', '2015-09-02'),
(645, 14, 38, 1, '2015-09-02', '2015-09-02'),
(646, 14, 39, 1, '2015-09-02', '2015-09-02'),
(872, 16, 1, 0, '2015-09-02', '2015-09-02'),
(873, 16, 2, 0, '2015-09-02', '2015-09-02'),
(874, 16, 3, 1, '2015-09-02', '2015-09-02'),
(875, 16, 4, 1, '2015-09-02', '2015-09-02'),
(876, 16, 5, 1, '2015-09-02', '2015-09-02'),
(877, 16, 6, 1, '2015-09-02', '2015-09-02'),
(878, 16, 7, 1, '2015-09-02', '2015-09-02'),
(879, 16, 8, 1, '2015-09-02', '2015-09-02'),
(880, 16, 9, 1, '2015-09-02', '2015-09-02'),
(881, 16, 10, 1, '2015-09-02', '2015-09-02'),
(882, 16, 11, 1, '2015-09-02', '2015-09-02'),
(883, 16, 13, 1, '2015-09-02', '2015-09-02'),
(884, 16, 15, 1, '2015-09-02', '2015-09-02'),
(885, 16, 16, 1, '2015-09-02', '2015-09-02'),
(886, 16, 17, 1, '2015-09-02', '2015-09-02'),
(887, 16, 22, 1, '2015-09-02', '2015-09-02'),
(888, 16, 23, 1, '2015-09-02', '2015-09-02'),
(889, 16, 24, 1, '2015-09-02', '2015-09-02'),
(890, 16, 25, 1, '2015-09-02', '2015-09-02'),
(891, 16, 26, 1, '2015-09-02', '2015-09-02'),
(892, 16, 27, 1, '2015-09-02', '2015-09-02'),
(893, 16, 29, 1, '2015-09-02', '2015-09-02'),
(894, 16, 30, 1, '2015-09-02', '2015-09-02'),
(895, 16, 31, 1, '2015-09-02', '2015-09-02'),
(896, 16, 32, 1, '2015-09-02', '2015-09-02'),
(897, 16, 33, 1, '2015-09-02', '2015-09-02'),
(898, 16, 34, 1, '2015-09-02', '2015-09-02'),
(899, 16, 35, 1, '2015-09-02', '2015-09-02'),
(900, 16, 36, 1, '2015-09-02', '2015-09-02'),
(901, 16, 37, 1, '2015-09-02', '2015-09-02'),
(902, 16, 38, 1, '2015-09-02', '2015-09-02'),
(903, 16, 39, 1, '2015-09-02', '2015-09-02'),
(905, 2, 1, 1, '2015-09-02', '2015-12-31'),
(906, 2, 2, 1, '2015-09-02', '2015-12-31'),
(907, 2, 3, 1, '2015-09-02', '2015-12-31'),
(908, 2, 4, 1, '2015-09-02', '2015-12-31'),
(909, 2, 5, 1, '2015-09-02', '2015-12-31'),
(910, 2, 6, 1, '2015-09-02', '2015-12-31'),
(911, 2, 7, 1, '2015-09-02', '2015-12-31'),
(912, 2, 8, 1, '2015-09-02', '2015-12-31'),
(913, 2, 9, 1, '2015-09-02', '2015-12-31'),
(914, 2, 10, 1, '2015-09-02', '2015-12-31'),
(915, 2, 11, 1, '2015-09-02', '2015-12-31'),
(916, 2, 13, 1, '2015-09-02', '2015-12-31'),
(917, 2, 15, 1, '2015-09-02', '2015-12-31'),
(918, 2, 16, 1, '2015-09-02', '2015-12-31'),
(919, 2, 17, 1, '2015-09-02', '2015-12-31'),
(920, 2, 22, 1, '2015-09-02', '2015-12-31'),
(921, 2, 23, 1, '2015-09-02', '2015-12-31'),
(922, 2, 24, 1, '2015-09-02', '2015-12-31'),
(923, 2, 25, 1, '2015-09-02', '2015-12-31'),
(924, 2, 26, 1, '2015-09-02', '2015-12-31'),
(925, 2, 27, 1, '2015-09-02', '2015-12-31'),
(926, 2, 29, 1, '2015-09-02', '2015-12-31'),
(927, 2, 30, 1, '2015-09-02', '2015-12-31'),
(928, 2, 31, 1, '2015-09-02', '2015-09-02'),
(929, 2, 32, 1, '2015-09-02', '2015-12-31'),
(930, 2, 33, 1, '2015-09-02', '2015-09-02'),
(931, 2, 34, 1, '2015-09-02', '2015-12-31'),
(932, 2, 35, 1, '2015-09-02', '2015-12-31'),
(933, 2, 36, 1, '2015-09-02', '2015-12-31'),
(934, 2, 37, 1, '2015-09-02', '2015-12-31'),
(935, 2, 38, 1, '2015-09-02', '2015-12-31'),
(936, 2, 39, 1, '2015-09-02', '2015-12-31'),
(937, 1, 1, 1, '2015-09-03', '2015-10-21'),
(938, 1, 2, 1, '2015-09-03', '2015-10-21'),
(939, 1, 3, 1, '2015-09-03', '2015-10-21'),
(940, 1, 4, 1, '2015-09-03', '2015-10-21'),
(941, 1, 5, 1, '2015-09-03', '2015-10-21'),
(942, 1, 6, 1, '2015-09-03', '2015-10-21'),
(943, 1, 7, 1, '2015-09-03', '2015-10-21'),
(944, 1, 8, 1, '2015-09-03', '2015-10-21'),
(945, 1, 9, 1, '2015-09-03', '2015-10-21'),
(946, 1, 10, 1, '2015-09-03', '2015-10-21'),
(947, 1, 11, 1, '2015-09-03', '2015-10-21'),
(948, 1, 13, 1, '2015-09-03', '2015-10-21'),
(949, 1, 15, 1, '2015-09-03', '2015-10-21'),
(950, 1, 16, 1, '2015-09-03', '2015-10-21'),
(951, 1, 17, 1, '2015-09-03', '2015-10-21'),
(952, 1, 22, 1, '2015-09-03', '2015-10-21'),
(953, 1, 23, 1, '2015-09-03', '2015-10-21'),
(954, 1, 24, 1, '2015-09-03', '2015-10-21'),
(955, 1, 25, 1, '2015-09-03', '2015-10-21'),
(956, 1, 26, 1, '2015-09-03', '2015-10-21'),
(957, 1, 27, 1, '2015-09-03', '2015-10-21'),
(958, 1, 29, 1, '2015-09-03', '2015-10-21'),
(959, 1, 30, 1, '2015-09-03', '2015-10-21'),
(960, 1, 31, 1, '2015-09-03', '2015-09-03'),
(961, 1, 32, 1, '2015-09-03', '2015-10-21'),
(962, 1, 33, 1, '2015-09-03', '2015-09-03'),
(963, 1, 34, 1, '2015-09-03', '2015-10-21'),
(964, 1, 35, 1, '2015-09-03', '2015-10-21'),
(965, 1, 36, 1, '2015-09-03', '2015-10-21'),
(966, 1, 37, 1, '2015-09-03', '2015-10-21'),
(967, 1, 38, 1, '2015-09-03', '2015-10-21'),
(968, 1, 39, 1, '2015-09-03', '2015-10-21'),
(969, 17, 1, 1, '2015-09-07', '2015-09-07'),
(970, 17, 2, 1, '2015-09-07', '2015-09-07'),
(971, 17, 3, 1, '2015-09-07', '2015-09-07'),
(972, 17, 4, 1, '2015-09-07', '2015-09-07'),
(973, 17, 5, 1, '2015-09-07', '2015-09-07'),
(974, 17, 6, 1, '2015-09-07', '2015-09-07'),
(975, 17, 7, 1, '2015-09-07', '2015-09-07'),
(976, 17, 8, 1, '2015-09-07', '2015-09-07'),
(977, 17, 9, 1, '2015-09-07', '2015-09-07'),
(978, 17, 10, 1, '2015-09-07', '2015-09-07'),
(979, 17, 11, 1, '2015-09-07', '2015-09-07'),
(980, 17, 13, 1, '2015-09-07', '2015-09-07'),
(981, 17, 15, 1, '2015-09-07', '2015-09-07'),
(982, 17, 16, 1, '2015-09-07', '2015-09-07'),
(983, 17, 17, 1, '2015-09-07', '2015-09-07'),
(984, 17, 22, 1, '2015-09-07', '2015-09-07'),
(985, 17, 23, 1, '2015-09-07', '2015-09-07'),
(986, 17, 24, 1, '2015-09-07', '2015-09-07'),
(987, 17, 25, 1, '2015-09-07', '2015-09-07'),
(988, 17, 26, 1, '2015-09-07', '2015-09-07'),
(989, 17, 27, 1, '2015-09-07', '2015-09-07'),
(990, 17, 29, 1, '2015-09-07', '2015-09-07'),
(991, 17, 30, 1, '2015-09-07', '2015-09-07'),
(992, 17, 31, 1, '2015-09-07', '2015-09-07'),
(993, 17, 32, 1, '2015-09-07', '2015-09-07'),
(994, 17, 33, 1, '2015-09-07', '2015-09-07'),
(995, 17, 34, 1, '2015-09-07', '2015-09-07'),
(996, 17, 35, 1, '2015-09-07', '2015-09-07'),
(997, 17, 36, 1, '2015-09-07', '2015-09-07'),
(998, 17, 37, 1, '2015-09-07', '2015-09-07'),
(999, 17, 38, 1, '2015-09-07', '2015-09-07'),
(1000, 17, 39, 1, '2015-09-07', '2015-09-07'),
(1001, 1, 40, 1, '2015-09-08', '2015-10-21'),
(1002, 1, 41, 1, '2015-09-14', '2015-10-21'),
(1003, 1, 42, 1, '2015-09-14', '2015-10-21'),
(1004, 2, 40, 1, '2015-09-14', '2015-12-31'),
(1005, 2, 41, 1, '2015-09-14', '2015-12-31'),
(1006, 2, 42, 1, '2015-09-14', '2015-12-31'),
(1007, 18, 1, 0, '2015-09-22', '2015-09-22'),
(1008, 18, 2, 0, '2015-09-22', '2015-09-22'),
(1009, 18, 3, 0, '2015-09-22', '2015-09-22'),
(1010, 18, 4, 0, '2015-09-22', '2015-09-22'),
(1011, 18, 5, 0, '2015-09-22', '2015-09-22'),
(1012, 18, 6, 0, '2015-09-22', '2015-09-22'),
(1013, 18, 7, 0, '2015-09-22', '2015-09-22'),
(1014, 18, 8, 0, '2015-09-22', '2015-09-22'),
(1015, 18, 9, 0, '2015-09-22', '2015-09-22'),
(1016, 18, 10, 0, '2015-09-22', '2015-09-22'),
(1017, 18, 11, 1, '2015-09-22', '2015-09-22'),
(1018, 18, 13, 1, '2015-09-22', '2015-09-22'),
(1019, 18, 15, 1, '2015-09-22', '2015-09-22'),
(1020, 18, 16, 1, '2015-09-22', '2015-09-22'),
(1021, 18, 17, 1, '2015-09-22', '2015-09-22'),
(1022, 18, 22, 0, '2015-09-22', '2015-09-22'),
(1023, 18, 23, 0, '2015-09-22', '2015-09-22'),
(1024, 18, 24, 0, '2015-09-22', '2015-09-22'),
(1025, 18, 25, 0, '2015-09-22', '2015-09-22'),
(1026, 18, 26, 0, '2015-09-22', '2015-09-22'),
(1027, 18, 27, 0, '2015-09-22', '2015-09-22'),
(1028, 18, 29, 0, '2015-09-22', '2015-09-22'),
(1029, 18, 30, 0, '2015-09-22', '2015-09-22'),
(1030, 18, 32, 0, '2015-09-22', '2015-09-22'),
(1031, 18, 34, 0, '2015-09-22', '2015-09-22'),
(1032, 18, 35, 0, '2015-09-22', '2015-09-22'),
(1033, 18, 36, 0, '2015-09-22', '2015-09-22'),
(1034, 18, 37, 0, '2015-09-22', '2015-09-22'),
(1035, 18, 38, 0, '2015-09-22', '2015-09-22'),
(1036, 18, 39, 0, '2015-09-22', '2015-09-22'),
(1037, 18, 40, 0, '2015-09-22', '2015-09-22'),
(1038, 18, 41, 0, '2015-09-22', '2015-09-22'),
(1039, 18, 42, 0, '2015-09-22', '2015-09-22'),
(1040, 19, 1, 0, '2015-09-22', '2015-09-22'),
(1041, 19, 2, 0, '2015-09-22', '2015-09-22'),
(1042, 19, 3, 0, '2015-09-22', '2015-09-22'),
(1043, 19, 4, 0, '2015-09-22', '2015-09-22'),
(1044, 19, 5, 0, '2015-09-22', '2015-09-22'),
(1045, 19, 6, 0, '2015-09-22', '2015-09-22'),
(1046, 19, 7, 0, '2015-09-22', '2015-09-22'),
(1047, 19, 8, 0, '2015-09-22', '2015-09-22'),
(1048, 19, 9, 0, '2015-09-22', '2015-09-22'),
(1049, 19, 10, 0, '2015-09-22', '2015-09-22'),
(1050, 19, 11, 0, '2015-09-22', '2015-09-22'),
(1051, 19, 13, 0, '2015-09-22', '2015-09-22'),
(1052, 19, 15, 0, '2015-09-22', '2015-09-22'),
(1053, 19, 16, 0, '2015-09-22', '2015-09-22'),
(1054, 19, 17, 0, '2015-09-22', '2015-09-22'),
(1055, 19, 22, 0, '2015-09-22', '2015-09-22'),
(1056, 19, 23, 0, '2015-09-22', '2015-09-22'),
(1057, 19, 24, 0, '2015-09-22', '2015-09-22'),
(1058, 19, 25, 0, '2015-09-22', '2015-09-22'),
(1059, 19, 26, 1, '2015-09-22', '2015-09-22'),
(1060, 19, 27, 1, '2015-09-22', '2015-09-22'),
(1061, 19, 29, 0, '2015-09-22', '2015-09-22'),
(1062, 19, 30, 0, '2015-09-22', '2015-09-22'),
(1063, 19, 32, 0, '2015-09-22', '2015-09-22'),
(1064, 19, 34, 0, '2015-09-22', '2015-09-22'),
(1065, 19, 35, 0, '2015-09-22', '2015-09-22'),
(1066, 19, 36, 0, '2015-09-22', '2015-09-22'),
(1067, 19, 37, 0, '2015-09-22', '2015-09-22'),
(1068, 19, 38, 0, '2015-09-22', '2015-09-22'),
(1069, 19, 39, 0, '2015-09-22', '2015-09-22'),
(1070, 19, 40, 0, '2015-09-22', '2015-09-22'),
(1071, 19, 41, 0, '2015-09-22', '2015-09-22'),
(1072, 19, 42, 0, '2015-09-22', '2015-09-22'),
(1073, 20, 1, 0, '2015-09-22', '2015-09-22'),
(1074, 20, 2, 0, '2015-09-22', '2015-09-22'),
(1075, 20, 3, 0, '2015-09-22', '2015-09-22'),
(1076, 20, 4, 0, '2015-09-22', '2015-09-22'),
(1077, 20, 5, 0, '2015-09-22', '2015-09-22'),
(1078, 20, 6, 0, '2015-09-22', '2015-09-22'),
(1079, 20, 7, 0, '2015-09-22', '2015-09-22'),
(1080, 20, 8, 0, '2015-09-22', '2015-09-22'),
(1081, 20, 9, 0, '2015-09-22', '2015-09-22'),
(1082, 20, 10, 0, '2015-09-22', '2015-09-22'),
(1083, 20, 11, 0, '2015-09-22', '2015-09-22'),
(1084, 20, 13, 0, '2015-09-22', '2015-09-22'),
(1085, 20, 15, 0, '2015-09-22', '2015-09-22'),
(1086, 20, 16, 0, '2015-09-22', '2015-09-22'),
(1087, 20, 17, 0, '2015-09-22', '2015-09-22'),
(1088, 20, 22, 0, '2015-09-22', '2015-09-22'),
(1089, 20, 23, 0, '2015-09-22', '2015-09-22'),
(1090, 20, 24, 0, '2015-09-22', '2015-09-22'),
(1091, 20, 25, 0, '2015-09-22', '2015-09-22'),
(1092, 20, 26, 1, '2015-09-22', '2015-09-22'),
(1093, 20, 27, 1, '2015-09-22', '2015-09-22'),
(1094, 20, 29, 0, '2015-09-22', '2015-09-22'),
(1095, 20, 30, 0, '2015-09-22', '2015-09-22'),
(1096, 20, 32, 0, '2015-09-22', '2015-09-22'),
(1097, 20, 34, 0, '2015-09-22', '2015-09-22'),
(1098, 20, 35, 0, '2015-09-22', '2015-09-22'),
(1099, 20, 36, 0, '2015-09-22', '2015-09-22'),
(1100, 20, 37, 0, '2015-09-22', '2015-09-22'),
(1101, 20, 38, 0, '2015-09-22', '2015-09-22'),
(1102, 20, 39, 0, '2015-09-22', '2015-09-22'),
(1103, 20, 40, 0, '2015-09-22', '2015-09-22'),
(1104, 20, 41, 0, '2015-09-22', '2015-09-22'),
(1105, 20, 42, 0, '2015-09-22', '2015-09-22'),
(1106, 21, 1, 0, '2015-09-30', '2015-09-30'),
(1107, 21, 2, 0, '2015-09-30', '2015-09-30'),
(1108, 21, 3, 0, '2015-09-30', '2015-09-30'),
(1109, 21, 4, 0, '2015-09-30', '2015-09-30'),
(1110, 21, 5, 0, '2015-09-30', '2015-09-30'),
(1111, 21, 6, 0, '2015-09-30', '2015-09-30'),
(1112, 21, 7, 0, '2015-09-30', '2015-09-30'),
(1113, 21, 8, 0, '2015-09-30', '2015-09-30'),
(1114, 21, 9, 0, '2015-09-30', '2015-09-30'),
(1115, 21, 10, 0, '2015-09-30', '2015-09-30'),
(1116, 21, 11, 1, '2015-09-30', '2015-09-30'),
(1117, 21, 13, 1, '2015-09-30', '2015-09-30'),
(1118, 21, 15, 1, '2015-09-30', '2015-09-30'),
(1119, 21, 16, 1, '2015-09-30', '2015-09-30'),
(1120, 21, 17, 1, '2015-09-30', '2015-09-30'),
(1121, 21, 22, 0, '2015-09-30', '2015-09-30'),
(1122, 21, 23, 0, '2015-09-30', '2015-09-30'),
(1123, 21, 24, 0, '2015-09-30', '2015-09-30'),
(1124, 21, 25, 0, '2015-09-30', '2015-09-30'),
(1125, 21, 26, 0, '2015-09-30', '2015-09-30'),
(1126, 21, 27, 0, '2015-09-30', '2015-09-30'),
(1127, 21, 29, 0, '2015-09-30', '2015-09-30'),
(1128, 21, 30, 0, '2015-09-30', '2015-09-30'),
(1129, 21, 32, 0, '2015-09-30', '2015-09-30'),
(1130, 21, 34, 0, '2015-09-30', '2015-09-30'),
(1131, 21, 35, 0, '2015-09-30', '2015-09-30'),
(1132, 21, 36, 0, '2015-09-30', '2015-09-30'),
(1133, 21, 37, 0, '2015-09-30', '2015-09-30'),
(1134, 21, 38, 0, '2015-09-30', '2015-09-30'),
(1135, 21, 39, 0, '2015-09-30', '2015-09-30'),
(1136, 21, 40, 0, '2015-09-30', '2015-09-30'),
(1137, 21, 41, 0, '2015-09-30', '2015-09-30'),
(1138, 21, 42, 0, '2015-09-30', '2015-09-30'),
(1139, 22, 1, 0, '2015-09-30', '2015-09-30'),
(1140, 22, 2, 0, '2015-09-30', '2015-09-30'),
(1141, 22, 3, 0, '2015-09-30', '2015-09-30'),
(1142, 22, 4, 0, '2015-09-30', '2015-09-30'),
(1143, 22, 5, 0, '2015-09-30', '2015-09-30'),
(1144, 22, 6, 0, '2015-09-30', '2015-09-30'),
(1145, 22, 7, 0, '2015-09-30', '2015-09-30'),
(1146, 22, 8, 0, '2015-09-30', '2015-09-30'),
(1147, 22, 9, 0, '2015-09-30', '2015-09-30'),
(1148, 22, 10, 0, '2015-09-30', '2015-09-30'),
(1149, 22, 11, 0, '2015-09-30', '2015-09-30'),
(1150, 22, 13, 0, '2015-09-30', '2015-09-30'),
(1151, 22, 15, 0, '2015-09-30', '2015-09-30'),
(1152, 22, 16, 0, '2015-09-30', '2015-09-30'),
(1153, 22, 17, 0, '2015-09-30', '2015-09-30'),
(1154, 22, 22, 0, '2015-09-30', '2015-09-30'),
(1155, 22, 23, 0, '2015-09-30', '2015-09-30'),
(1156, 22, 24, 0, '2015-09-30', '2015-09-30'),
(1157, 22, 25, 0, '2015-09-30', '2015-09-30'),
(1158, 22, 26, 1, '2015-09-30', '2015-09-30'),
(1159, 22, 27, 1, '2015-09-30', '2015-09-30'),
(1160, 22, 29, 0, '2015-09-30', '2015-09-30'),
(1161, 22, 30, 0, '2015-09-30', '2015-09-30'),
(1162, 22, 32, 0, '2015-09-30', '2015-09-30'),
(1163, 22, 34, 0, '2015-09-30', '2015-09-30'),
(1164, 22, 35, 0, '2015-09-30', '2015-09-30'),
(1165, 22, 36, 0, '2015-09-30', '2015-09-30'),
(1166, 22, 37, 0, '2015-09-30', '2015-09-30'),
(1167, 22, 38, 0, '2015-09-30', '2015-09-30'),
(1168, 22, 39, 0, '2015-09-30', '2015-09-30'),
(1169, 22, 40, 0, '2015-09-30', '2015-09-30'),
(1170, 22, 41, 0, '2015-09-30', '2015-09-30'),
(1171, 22, 42, 0, '2015-09-30', '2015-09-30'),
(1172, 1, 43, 1, '2015-10-21', '2015-10-21'),
(1173, 23, 1, 1, '2015-10-27', '2015-10-27'),
(1174, 23, 2, 1, '2015-10-27', '2015-10-27'),
(1175, 23, 3, 1, '2015-10-27', '2015-10-27'),
(1176, 23, 4, 1, '2015-10-27', '2015-10-27'),
(1177, 23, 5, 1, '2015-10-27', '2015-10-27'),
(1178, 23, 6, 1, '2015-10-27', '2015-10-27'),
(1179, 23, 7, 1, '2015-10-27', '2015-10-27'),
(1180, 23, 8, 1, '2015-10-27', '2015-10-27'),
(1181, 23, 9, 1, '2015-10-27', '2015-10-27'),
(1182, 23, 10, 1, '2015-10-27', '2015-10-27'),
(1183, 23, 11, 1, '2015-10-27', '2015-10-27'),
(1184, 23, 13, 1, '2015-10-27', '2015-10-27'),
(1185, 23, 15, 1, '2015-10-27', '2015-10-27'),
(1186, 23, 16, 1, '2015-10-27', '2015-10-27'),
(1187, 23, 17, 1, '2015-10-27', '2015-10-27'),
(1188, 23, 22, 1, '2015-10-27', '2015-10-27'),
(1189, 23, 23, 1, '2015-10-27', '2015-10-27'),
(1190, 23, 24, 1, '2015-10-27', '2015-10-27'),
(1191, 23, 25, 1, '2015-10-27', '2015-10-27'),
(1192, 23, 26, 1, '2015-10-27', '2015-10-27'),
(1193, 23, 27, 1, '2015-10-27', '2015-10-27'),
(1194, 23, 29, 1, '2015-10-27', '2015-10-27'),
(1195, 23, 30, 1, '2015-10-27', '2015-10-27'),
(1196, 23, 32, 1, '2015-10-27', '2015-10-27'),
(1197, 23, 34, 1, '2015-10-27', '2015-10-27'),
(1198, 23, 35, 1, '2015-10-27', '2015-10-27'),
(1199, 23, 36, 1, '2015-10-27', '2015-10-27'),
(1200, 23, 37, 1, '2015-10-27', '2015-10-27'),
(1201, 23, 38, 1, '2015-10-27', '2015-10-27'),
(1202, 23, 39, 1, '2015-10-27', '2015-10-27'),
(1203, 23, 40, 1, '2015-10-27', '2015-10-27'),
(1204, 23, 41, 1, '2015-10-27', '2015-10-27'),
(1205, 23, 42, 1, '2015-10-27', '2015-10-27'),
(1206, 23, 43, 1, '2015-10-27', '2015-10-27'),
(1207, 2, 43, 1, '2015-12-31', '2015-12-31'),
(1208, 3, 1, 1, '2015-12-31', '2015-12-31'),
(1209, 3, 2, 1, '2015-12-31', '2015-12-31'),
(1210, 3, 3, 1, '2015-12-31', '2015-12-31'),
(1211, 3, 4, 1, '2015-12-31', '2015-12-31'),
(1212, 3, 5, 1, '2015-12-31', '2015-12-31'),
(1213, 3, 6, 1, '2015-12-31', '2015-12-31'),
(1214, 3, 7, 1, '2015-12-31', '2015-12-31'),
(1215, 3, 8, 1, '2015-12-31', '2015-12-31'),
(1216, 3, 9, 1, '2015-12-31', '2015-12-31'),
(1217, 3, 10, 1, '2015-12-31', '2015-12-31'),
(1218, 3, 11, 1, '2015-12-31', '2015-12-31'),
(1219, 3, 13, 1, '2015-12-31', '2015-12-31'),
(1220, 3, 15, 1, '2015-12-31', '2015-12-31'),
(1221, 3, 16, 1, '2015-12-31', '2015-12-31'),
(1222, 3, 17, 1, '2015-12-31', '2015-12-31'),
(1223, 3, 22, 1, '2015-12-31', '2015-12-31'),
(1224, 3, 23, 1, '2015-12-31', '2015-12-31'),
(1225, 3, 24, 1, '2015-12-31', '2015-12-31'),
(1226, 3, 25, 1, '2015-12-31', '2015-12-31'),
(1227, 3, 26, 1, '2015-12-31', '2015-12-31'),
(1228, 3, 27, 1, '2015-12-31', '2015-12-31'),
(1229, 3, 29, 1, '2015-12-31', '2015-12-31'),
(1230, 3, 30, 1, '2015-12-31', '2015-12-31'),
(1231, 3, 32, 1, '2015-12-31', '2015-12-31'),
(1232, 3, 34, 1, '2015-12-31', '2015-12-31'),
(1233, 3, 35, 1, '2015-12-31', '2015-12-31'),
(1234, 3, 36, 1, '2015-12-31', '2015-12-31'),
(1235, 3, 37, 1, '2015-12-31', '2015-12-31'),
(1236, 3, 38, 1, '2015-12-31', '2015-12-31'),
(1237, 3, 39, 1, '2015-12-31', '2015-12-31'),
(1238, 3, 40, 1, '2015-12-31', '2015-12-31'),
(1239, 3, 41, 1, '2015-12-31', '2015-12-31'),
(1240, 3, 42, 1, '2015-12-31', '2015-12-31'),
(1241, 3, 43, 1, '2015-12-31', '2015-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE IF NOT EXISTS `voucher` (
`id` int(11) NOT NULL,
  `vnno` text COLLATE utf32_bin NOT NULL,
  `vdate` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` int(10) NOT NULL,
  `type` int(11) NOT NULL,
  `sid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `vstatus` int(11) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`id`, `vnno`, `vdate`, `amount`, `status`, `type`, `sid`, `cid`, `vstatus`, `userid`, `created_at`, `updated_at`) VALUES
(1, 'v-7789', '2015-10-26', '7500.00', 2, 2, 4, NULL, 1, 1, '2015-10-26', '2015-10-26'),
(2, 'v-16163', '2015-10-26', '3000.00', 1, 4, NULL, 1, 1, 1, '2015-10-26', '2015-10-26'),
(3, 'v-18643', '2015-10-26', '1500.00', 1, 6, NULL, 30, 1, 1, '2015-10-26', '2015-10-26'),
(4, 'v-3924', '2015-10-26', '2300.00', 1, 7, NULL, 34, 1, 1, '2015-10-26', '2015-10-26'),
(5, 'v-22410', '2015-10-26', '6390.00', 1, 8, NULL, 30, 1, 1, '2015-10-26', '2015-10-26'),
(6, 'v-25022', '2015-10-26', '1700.00', 1, 9, NULL, 1, 1, 1, '2015-10-26', '2015-10-26'),
(7, 'v-31792', '2015-10-26', '1800.00', 1, 5, NULL, NULL, 1, 1, '2015-10-26', '2015-10-26'),
(8, 'v-24555', '2015-10-26', '4700.00', 2, 5, NULL, NULL, 1, 1, '2015-10-26', '2015-10-26'),
(9, 'v-31084', '2015-10-26', '2500.00', 2, 2, 11, NULL, 1, 1, '2015-10-26', '2015-10-27'),
(10, 'v-30091', '2015-10-26', '1200.00', 1, 5, NULL, NULL, 1, 1, '2015-10-26', '2015-10-26'),
(11, 'v-24423', '2015-10-26', '1300.00', 2, 5, NULL, NULL, 1, 1, '2015-10-26', '2015-10-26'),
(12, 'v-23636', '2015-10-27', '25000.00', 1, 6, NULL, 1, 0, 1, '2015-10-27', '2015-10-27'),
(13, 'v-20585', '2015-10-27', '32000.00', 2, 1, 4, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(14, 'v-20496', '2015-10-27', '7500.00', 2, 2, 2, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(15, 'v-13272', '2015-10-27', '50000.00', 1, 9, NULL, 1, 1, 1, '2015-10-27', '2015-10-27'),
(16, 'v-28205', '2015-10-27', '1200.00', 1, 4, NULL, 1, 1, 1, '2015-10-27', '2015-10-27'),
(17, 'v-20425', '2015-10-27', '10000.00', 1, 5, NULL, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(18, 'v-8069', '2015-10-27', '5200.00', 2, 5, NULL, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(19, 'v-32578', '2015-10-27', '2500.00', 3, 5, NULL, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(20, 'v-20029', '2015-10-27', '6300.00', 2, 1, 4, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(21, 'v-27635', '2015-10-27', '20000.00', 1, 3, NULL, 33, 1, 1, '2015-10-27', '2015-10-27'),
(22, 'v-5138', '2015-10-27', '8250.00', 2, 1, 4, NULL, 0, 1, '2015-10-27', '2015-10-27'),
(23, 'v-5138', '2015-10-27', '8250.00', 2, 1, 4, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(24, 'v-31798', '2015-10-27', '3200.00', 1, 3, NULL, 1, 1, 1, '2015-10-27', '2015-10-27'),
(25, 'v-11437', '2015-10-27', '6450.00', 1, 5, NULL, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(26, 'v-23395', '2015-10-27', '2800.00', 2, 5, NULL, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(27, 'v-24294', '2015-10-27', '13000.00', 2, 5, NULL, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(28, 'v-882', '2015-10-27', '3333.00', 3, 5, NULL, NULL, 0, 1, '2015-10-27', '2015-10-27'),
(29, 'v-6588', '2015-10-27', '5600.00', 2, 1, 4, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(30, 'v-25173', '2015-10-27', '4500.00', 1, 3, NULL, 31, 0, 1, '2015-10-27', '2015-10-27'),
(31, 'v-24092', '2015-10-27', '3200.00', 1, 5, NULL, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(32, 'v-18243', '2015-10-27', '7600.00', 2, 5, NULL, NULL, 1, 1, '2015-10-27', '2015-10-27'),
(33, 'v-25505', '2015-10-27', '4300.00', 3, 5, NULL, NULL, 0, 1, '2015-10-27', '2015-10-27'),
(34, 'v-5279', '2015-10-27', '7500.00', 3, 5, NULL, NULL, 0, 1, '2015-10-27', '2015-10-27'),
(35, 'v-19399', '2015-10-28', '18000.00', 1, 9, NULL, 29, 0, 1, '2015-10-28', '2015-10-28'),
(36, 'v-25803', '2015-10-28', '67000.00', 1, 8, NULL, 1, 1, 1, '2015-10-28', '2015-10-28'),
(37, 'v-29663', '2015-10-28', '59000.00', 1, 7, NULL, 30, 1, 1, '2015-10-28', '2015-10-28'),
(38, 'v-29526', '2015-10-28', '54000.00', 1, 6, NULL, 33, 1, 1, '2015-10-28', '2015-10-28'),
(39, 'v-28429', '2015-10-28', '23000.00', 1, 4, NULL, 29, 0, 1, '2015-10-28', '2015-10-28'),
(40, 'v-320', '2015-10-28', '18000.00', 1, 3, NULL, 1, 1, 1, '2015-10-28', '2015-10-28'),
(41, 'v-5445', '2015-10-28', '59000.00', 2, 2, 4, NULL, 1, 1, '2015-10-28', '2015-10-28'),
(42, 'v-4888', '2015-10-28', '59000.00', 2, 1, 11, NULL, 1, 1, '2015-10-28', '2015-10-28'),
(43, 'v-5001', '2015-10-28', '59000.00', 1, 5, NULL, NULL, 1, 1, '2015-10-28', '2015-10-28'),
(44, 'v-10945', '2015-10-28', '23000.00', 2, 5, NULL, NULL, 1, 1, '2015-10-28', '2015-10-28'),
(45, 'v-516', '2015-10-28', '78000.00', 3, 5, NULL, NULL, 1, 1, '2015-10-28', '2015-10-28'),
(46, 'v-2492', '2015-10-29', '10000.00', 1, 4, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(47, 'v-1024', '2015-10-29', '5000.00', 1, 4, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(48, 'v-27069', '2015-10-29', '4000.00', 1, 3, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(49, 'v-7454', '2015-10-29', '4000.00', 1, 5, NULL, NULL, 1, 1, '2015-10-29', '2015-10-29'),
(50, 'v-27127', '2015-10-29', '4000.00', 2, 5, NULL, NULL, 1, 1, '2015-10-29', '2015-10-29'),
(51, 'v-2244', '2015-10-29', '10000.00', 1, 6, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(52, 'v-7013', '2015-10-29', '10000.00', 1, 7, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(53, 'v-4187', '2015-10-29', '10000.00', 1, 8, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(54, 'v-19038', '2015-10-29', '15000.00', 1, 8, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(55, 'v-2834', '2015-10-29', '15000.00', 1, 9, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(56, 'v-16638', '2015-10-29', '120000.00', 1, 4, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(57, 'v-2120', '2015-10-29', '130000.00', 1, 6, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(58, 'v-782', '2015-10-29', '74000.00', 1, 7, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(59, 'v-22312', '2015-10-29', '60000.00', 1, 4, NULL, 38, 1, 1, '2015-10-29', '2015-10-29'),
(60, 'v-29326', '2015-10-29', '53250.00', 1, 9, NULL, 41, 1, 1, '2015-10-29', '2015-10-29'),
(61, 'v-19396', '2015-11-01', '12000.00', 2, 1, 11, NULL, 1, 1, '2015-11-01', '2015-11-01'),
(62, 'v-9490', '2015-11-01', '34000.00', 2, 2, 2, NULL, 1, 1, '2015-11-01', '2015-11-01'),
(63, 'v-26957', '2015-11-01', '89000.00', 1, 3, NULL, 30, 0, 1, '2015-11-01', '2015-11-01'),
(64, 'v-8434', '2015-11-01', '65000.00', 1, 4, NULL, 34, 1, 1, '2015-11-01', '2015-11-01'),
(65, 'v-22287', '2015-11-01', '67000.00', 1, 6, NULL, 33, 0, 1, '2015-11-01', '2015-11-01'),
(66, 'v-31284', '2015-11-01', '78000.00', 1, 7, NULL, 1, 1, 1, '2015-11-01', '2015-11-01'),
(67, 'v-4358', '2015-11-01', '23000.00', 1, 8, NULL, 32, 1, 1, '2015-11-01', '2015-11-01'),
(68, 'v-10151', '2015-11-01', '78900.00', 1, 9, NULL, 29, 1, 1, '2015-11-01', '2015-11-01'),
(69, 'v-10475', '2015-11-02', '4000.00', 1, 6, NULL, 1, 1, 1, '2015-11-02', '2015-11-02'),
(70, 'v-21640', '2015-11-04', '6700.00', 1, 9, NULL, 30, 1, 1, '2015-11-04', '2015-11-04'),
(71, 'v-10398', '2015-11-04', '12000.00', 1, 8, NULL, 33, 1, 1, '2015-11-04', '2015-11-04');

-- --------------------------------------------------------

--
-- Table structure for table `voucherbankpayment`
--

CREATE TABLE IF NOT EXISTS `voucherbankpayment` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `baccid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `checkno` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `voucherbankpayment`
--

INSERT INTO `voucherbankpayment` (`id`, `vid`, `baccid`, `sid`, `checkno`, `userid`, `created_at`, `updated_at`) VALUES
(1, 13, 2, 4, '', 1, '2015-10-27', '2015-10-27'),
(2, 20, 29, 4, '148411', 1, '2015-10-27', '2015-10-27'),
(3, 22, 30, 4, '2154841', 1, '2015-10-27', '2015-10-27'),
(4, 23, 30, 4, '2154841', 1, '2015-10-27', '2015-10-27'),
(5, 29, 31, 4, '43556', 1, '2015-10-27', '2015-10-27'),
(6, 42, 4, 11, '', 1, '2015-10-28', '2015-10-28'),
(7, 61, 4, 11, '5454545', 1, '2015-11-01', '2015-11-01');

-- --------------------------------------------------------

--
-- Table structure for table `voucherbankreceive`
--

CREATE TABLE IF NOT EXISTS `voucherbankreceive` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `baccid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `checkno` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `voucherbankreceive`
--

INSERT INTO `voucherbankreceive` (`id`, `vid`, `baccid`, `cid`, `checkno`, `userid`, `created_at`, `updated_at`) VALUES
(1, 21, 29, 33, '48745777', 1, '2015-10-27 01:29:04', '2015-10-27 01:29:04'),
(2, 24, 30, 1, '', 1, '2015-10-27 02:18:39', '2015-10-27 02:18:39'),
(3, 30, 31, 31, '343654', 1, '2015-10-27 04:03:46', '2015-10-27 04:03:46'),
(4, 40, 2, 1, '', 1, '2015-10-28 01:04:29', '2015-10-28 01:04:29'),
(5, 48, 31, 38, '1245555', 1, '2015-10-29 00:29:44', '2015-10-29 00:29:44'),
(6, 63, 13, 30, '565666', 1, '2015-10-31 22:49:41', '2015-10-31 22:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `voucherbkash`
--

CREATE TABLE IF NOT EXISTS `voucherbkash` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `bkashno` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `voucherbkash`
--

INSERT INTO `voucherbkash` (`id`, `vid`, `bkashno`, `cid`, `userid`, `created_at`, `updated_at`) VALUES
(1, 3, 581444, 30, 1, '2015-10-26 03:21:51', '2015-10-26 03:21:51'),
(2, 12, 1793532035, 1, 1, '2015-10-26 22:30:46', '2015-10-26 22:30:46'),
(3, 38, 6578787, 33, 1, '2015-10-28 01:02:47', '2015-10-28 01:02:47'),
(4, 51, 15471, 38, 1, '2015-10-29 00:37:52', '2015-10-29 00:37:52'),
(5, 57, 12411, 38, 1, '2015-10-29 02:59:29', '2015-10-29 02:59:29'),
(6, 65, 54708989, 33, 1, '2015-10-31 22:50:28', '2015-10-31 22:50:28'),
(7, 69, 4353, 1, 1, '2015-11-02 05:00:47', '2015-11-02 05:00:47');

-- --------------------------------------------------------

--
-- Table structure for table `vouchercontra`
--

CREATE TABLE IF NOT EXISTS `vouchercontra` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `baccid` int(11) NOT NULL,
  `cashid` int(11) NOT NULL,
  `checkno` text COLLATE utf32_bin NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `vouchercontra`
--

INSERT INTO `vouchercontra` (`id`, `vid`, `baccid`, `cashid`, `checkno`, `userid`, `created_at`, `updated_at`) VALUES
(1, 7, 2, 1, '685414', 1, '2015-10-26 03:24:13', '2015-10-26 03:24:13'),
(2, 8, 4, 1, '6514114', 1, '2015-10-26 03:25:03', '2015-10-26 03:25:03'),
(3, 10, 2, 1, '76565', 1, '2015-10-26 04:05:24', '2015-10-26 04:05:24'),
(4, 11, 4, 1, '5465443', 1, '2015-10-26 04:11:27', '2015-10-26 04:11:27'),
(5, 17, 29, 1, '251010', 1, '2015-10-27 01:26:37', '2015-10-27 01:26:37'),
(6, 18, 29, 1, '4578141', 1, '2015-10-27 01:27:13', '2015-10-27 01:27:13'),
(7, 19, 4, 29, '6358741', 1, '2015-10-27 01:27:47', '2015-10-27 01:27:47'),
(8, 25, 30, 1, '141444', 1, '2015-10-27 02:21:56', '2015-10-27 02:21:56'),
(9, 26, 30, 1, '154441', 1, '2015-10-27 02:23:35', '2015-10-27 02:23:35'),
(10, 27, 30, 1, '14441', 1, '2015-10-27 02:25:37', '2015-10-27 02:25:37'),
(11, 28, 30, 2, '1444', 1, '2015-10-27 02:34:49', '2015-10-27 02:34:49'),
(12, 31, 31, 1, '34545', 1, '2015-10-27 04:08:32', '2015-10-27 04:08:32'),
(13, 32, 31, 1, '34567', 1, '2015-10-27 04:09:26', '2015-10-27 04:09:26'),
(14, 33, 31, 2, '3412', 1, '2015-10-27 04:10:38', '2015-10-27 04:10:38'),
(15, 34, 2, 31, '34545', 1, '2015-10-27 04:14:19', '2015-10-27 04:14:19'),
(16, 43, 4, 1, '76767', 1, '2015-10-28 01:33:13', '2015-10-28 01:33:13'),
(17, 44, 9, 1, '878787', 1, '2015-10-28 01:33:34', '2015-10-28 01:33:34'),
(18, 45, 10, 11, '6767676', 1, '2015-10-28 01:33:59', '2015-10-28 01:33:59'),
(19, 49, 31, 1, '15471414', 1, '2015-10-29 00:31:47', '2015-10-29 00:31:47'),
(20, 50, 31, 1, '14444', 1, '2015-10-29 00:33:25', '2015-10-29 00:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `voucherkcs`
--

CREATE TABLE IF NOT EXISTS `voucherkcs` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `kcsno` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voucherkcs`
--

INSERT INTO `voucherkcs` (`id`, `vid`, `kcsno`, `cid`, `userid`, `created_at`, `updated_at`) VALUES
(1, 5, 38511, 30, 1, '2015-10-26 03:22:55', '2015-10-26 03:22:55'),
(2, 36, 78787, 1, 1, '2015-10-28 01:02:03', '2015-10-28 01:02:03'),
(3, 53, 7874144, 38, 1, '2015-10-29 00:43:29', '2015-10-29 00:43:29'),
(4, 54, 15888, 38, 1, '2015-10-29 00:46:01', '2015-10-29 00:46:01'),
(5, 67, 6478700, 32, 1, '2015-10-31 22:51:31', '2015-10-31 22:51:31'),
(6, 71, 1236543, 33, 1, '2015-11-03 22:51:18', '2015-11-03 22:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `vouchermbank`
--

CREATE TABLE IF NOT EXISTS `vouchermbank` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `mbankno` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vouchermbank`
--

INSERT INTO `vouchermbank` (`id`, `vid`, `mbankno`, `cid`, `userid`, `created_at`, `updated_at`) VALUES
(1, 6, 1524, 1, 1, '2015-10-26 03:23:23', '2015-10-26 03:23:23'),
(2, 15, 548771, 1, 1, '2015-10-26 23:09:23', '2015-10-26 23:09:23'),
(3, 35, 23232, 29, 1, '2015-10-28 01:01:19', '2015-10-28 01:01:19'),
(4, 55, 1587788, 38, 1, '2015-10-29 00:46:35', '2015-10-29 00:46:35'),
(5, 60, 1799312444, 41, 1, '2015-10-29 04:17:20', '2015-10-29 04:17:20'),
(6, 68, 6589898, 29, 1, '2015-10-31 22:51:58', '2015-10-31 22:51:58'),
(7, 70, 2147483647, 30, 1, '2015-11-03 22:50:57', '2015-11-03 22:50:57');

-- --------------------------------------------------------

--
-- Table structure for table `voucherpayment`
--

CREATE TABLE IF NOT EXISTS `voucherpayment` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `baccid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `voucherpayment`
--

INSERT INTO `voucherpayment` (`id`, `vid`, `baccid`, `sid`, `userid`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 1, '2015-10-26 03:19:51', '2015-10-26 03:19:51'),
(2, 9, 1, 11, 1, '2015-10-26 03:51:43', '2015-10-26 03:51:43'),
(3, 14, 1, 2, 1, '2015-10-26 23:06:02', '2015-10-26 23:06:02'),
(4, 41, 1, 4, 1, '2015-10-28 01:04:58', '2015-10-28 01:04:58'),
(5, 62, 1, 2, 1, '2015-10-31 22:49:18', '2015-10-31 22:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `voucherreceive`
--

CREATE TABLE IF NOT EXISTS `voucherreceive` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `baccid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `voucherreceive`
--

INSERT INTO `voucherreceive` (`id`, `vid`, `baccid`, `cid`, `userid`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 1, '2015-10-26 03:20:14', '2015-10-26 03:20:14'),
(2, 16, 1, 1, 1, '2015-10-27 00:01:27', '2015-10-27 00:01:27'),
(3, 39, 1, 29, 1, '2015-10-28 01:03:08', '2015-10-28 01:03:08'),
(4, 46, 1, 38, 1, '2015-10-29 00:18:02', '2015-10-29 00:18:02'),
(5, 47, 1, 38, 1, '2015-10-29 00:22:09', '2015-10-29 00:22:09'),
(6, 56, 1, 38, 1, '2015-10-29 02:52:22', '2015-10-29 02:52:22'),
(7, 59, 1, 38, 1, '2015-10-29 03:24:03', '2015-10-29 03:24:03'),
(8, 64, 1, 34, 1, '2015-10-31 22:50:06', '2015-10-31 22:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `vouchersap`
--

CREATE TABLE IF NOT EXISTS `vouchersap` (
`id` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `sapno` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf32 COLLATE=utf32_bin;

--
-- Dumping data for table `vouchersap`
--

INSERT INTO `vouchersap` (`id`, `vid`, `sapno`, `cid`, `userid`, `created_at`, `updated_at`) VALUES
(1, 4, 6854, 34, 1, '2015-10-26 03:22:20', '2015-10-26 03:22:20'),
(2, 37, 89898, 30, 1, '2015-10-28 01:02:24', '2015-10-28 01:02:24'),
(3, 52, 474544, 38, 1, '2015-10-29 00:41:41', '2015-10-29 00:41:41'),
(4, 58, 2541414, 38, 1, '2015-10-29 03:20:22', '2015-10-29 03:20:22'),
(5, 66, 5682000, 1, 1, '2015-10-31 22:51:10', '2015-10-31 22:51:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bankaccount`
--
ALTER TABLE `bankaccount`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bankbook`
--
ALTER TABLE `bankbook`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bankinfo`
--
ALTER TABLE `bankinfo`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banktitle`
--
ALTER TABLE `banktitle`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billspay`
--
ALTER TABLE `billspay`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coa`
--
ALTER TABLE `coa`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coatype`
--
ALTER TABLE `coatype`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companyprofile`
--
ALTER TABLE `companyprofile`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customersledger`
--
ALTER TABLE `customersledger`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employeeinfo`
--
ALTER TABLE `employeeinfo`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employeesal`
--
ALTER TABLE `employeesal`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factioyitems`
--
ALTER TABLE `factioyitems`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `increasetype`
--
ALTER TABLE `increasetype`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `itemsgroup`
--
ALTER TABLE `itemsgroup`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `itemssubgroup`
--
ALTER TABLE `itemssubgroup`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measurementgroup`
--
ALTER TABLE `measurementgroup`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measurementunit`
--
ALTER TABLE `measurementunit`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `particulars`
--
ALTER TABLE `particulars`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pettycash`
--
ALTER TABLE `pettycash`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchasedetails`
--
ALTER TABLE `purchasedetails`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salesdetails`
--
ALTER TABLE `salesdetails`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submenus`
--
ALTER TABLE `submenus`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliersledger`
--
ALTER TABLE `suppliersledger`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxrate`
--
ALTER TABLE `taxrate`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usergroups`
--
ALTER TABLE `usergroups`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userspermission`
--
ALTER TABLE `userspermission`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucherbankpayment`
--
ALTER TABLE `voucherbankpayment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucherbankreceive`
--
ALTER TABLE `voucherbankreceive`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucherbkash`
--
ALTER TABLE `voucherbkash`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchercontra`
--
ALTER TABLE `vouchercontra`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucherkcs`
--
ALTER TABLE `voucherkcs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchermbank`
--
ALTER TABLE `vouchermbank`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucherpayment`
--
ALTER TABLE `voucherpayment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucherreceive`
--
ALTER TABLE `voucherreceive`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchersap`
--
ALTER TABLE `vouchersap`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bankaccount`
--
ALTER TABLE `bankaccount`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `bankbook`
--
ALTER TABLE `bankbook`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `bankinfo`
--
ALTER TABLE `bankinfo`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `banktitle`
--
ALTER TABLE `banktitle`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `billspay`
--
ALTER TABLE `billspay`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `coa`
--
ALTER TABLE `coa`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT for table `coatype`
--
ALTER TABLE `coatype`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `companyprofile`
--
ALTER TABLE `companyprofile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `customersledger`
--
ALTER TABLE `customersledger`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=333;
--
-- AUTO_INCREMENT for table `employeeinfo`
--
ALTER TABLE `employeeinfo`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `employeesal`
--
ALTER TABLE `employeesal`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `factioyitems`
--
ALTER TABLE `factioyitems`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT for table `increasetype`
--
ALTER TABLE `increasetype`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=132;
--
-- AUTO_INCREMENT for table `itemsgroup`
--
ALTER TABLE `itemsgroup`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `itemssubgroup`
--
ALTER TABLE `itemssubgroup`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `measurementgroup`
--
ALTER TABLE `measurementgroup`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `measurementunit`
--
ALTER TABLE `measurementunit`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `particulars`
--
ALTER TABLE `particulars`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `pettycash`
--
ALTER TABLE `pettycash`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT for table `purchasedetails`
--
ALTER TABLE `purchasedetails`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=99;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `salesdetails`
--
ALTER TABLE `salesdetails`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `submenus`
--
ALTER TABLE `submenus`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `suppliersledger`
--
ALTER TABLE `suppliersledger`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT for table `taxrate`
--
ALTER TABLE `taxrate`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `usergroups`
--
ALTER TABLE `usergroups`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `userspermission`
--
ALTER TABLE `userspermission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1242;
--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT for table `voucherbankpayment`
--
ALTER TABLE `voucherbankpayment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `voucherbankreceive`
--
ALTER TABLE `voucherbankreceive`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `voucherbkash`
--
ALTER TABLE `voucherbkash`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `vouchercontra`
--
ALTER TABLE `vouchercontra`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `voucherkcs`
--
ALTER TABLE `voucherkcs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `vouchermbank`
--
ALTER TABLE `vouchermbank`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `voucherpayment`
--
ALTER TABLE `voucherpayment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `voucherreceive`
--
ALTER TABLE `voucherreceive`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `vouchersap`
--
ALTER TABLE `vouchersap`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
