delete from WORKS_ON;
delete from DEPENDENT;
delete from PROJECT;
delete from DEPT_LOCATIONS;
delete from DEPARTMENT;
delete from EMPLOYEE;

drop table DEPT_LOCATIONS cascade;
drop table WORKS_ON cascade;
drop table PROJECT cascade;
drop table DEPENDENT cascade;
drop table DEPARTMENT cascade;
drop table EMPLOYEE cascade;