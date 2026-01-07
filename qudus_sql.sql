-- QUDUS DATABASE SCHEMA --

drop database qudus;
create database if not exists qudus
character set utf8mb4 collate utf8mb4_unicode_ci;
use qudus;

create table if not exists artifacts(
    id int auto_increment,
    name varchar(50) not null,
    description text,
    origin varchar(50),
    period varchar(50),
    material varchar(50),
    condition_status enum('Excellent', 'Good', 'Needs Maintenance'),
    location varchar(50),
    image_url varchar(255),
    acquisition_date date,

    constraint `artifacts_pk` primary key (id)
);

create table if not exists staff(
    id int auto_increment,
    first_name varchar(20) not null,
    last_name varchar(20) not null,
    role enum('Admin', 'Curator', 'Guide', 'Designer', 'Security', 'Volunteer') not null,
    email varchar(50) not null unique,
    phone varchar(15),
    hire_date date,

    constraint `staff_pk` primary key (id)
);

create table if not exists artists(
    id int auto_increment,
    first_name varchar(20) not null,
    last_name varchar(20) not null,
    email varchar(50) not null unique,
    bio text,

    constraint `artists_pk` primary key (id)
);

create table if not exists artworks(
    id int auto_increment,
    title varchar(50) not null,
    description text,
    artwork_type varchar(50),
    creation_year year,
    location varchar(100),
    image_url varchar(255),
    artist_id int,

    constraint `artworks_pk` primary key (id),
    constraint `artworks_artist_id_fk` foreign key (artist_id) references artists(id)
);

create table if not exists exhibitions(
    id int auto_increment,
    title varchar(50) not null,
    start_date date not null,
    end_date date not null,
    description text,
    status enum('Upcoming', 'Ongoing', 'Past') default 'Upcoming',
    manager_id int not null,

    constraint `exhibitions_pk` primary key (id),
    constraint `exhibitions_manager_id_fk` foreign key (manager_id) references staff(id)
);

create table if not exists visitors(
    id int auto_increment,
    first_name varchar(20) not null,
    last_name varchar(20) not null,
    email varchar(50) not null unique,
    phone varchar(15),

    constraint `visitors_pk` primary key (id)
);

create table if not exists visits(
    id int auto_increment,
    visitor_id int not null,
    exhibition_id int,
    visit_date timestamp default current_timestamp,
    feedback text,
    rating int check (rating between 1 and 5),

    constraint `visits_pk` primary key (id),
    constraint `visits_visitor_id_fk` foreign key (visitor_id) references visitors(id),
    constraint `visits_exhibition_id_fk` foreign key (exhibition_id) references exhibitions(id)
);

create table if not exists printer_requests(
    id int auto_increment,
    request_type varchar(100) not null,
    request_date timestamp default current_timestamp,
    status enum('Pending', 'Approved', 'Printed', 'Delivered', 'Rejected') default 'Pending',
    file_path varchar(255),
    designer_id int not null,

    constraint `requests_pk` primary key (id),
    constraint `requests_designer_id_fk` foreign key (designer_id) references staff(id)
);

create table if not exists exhibition_artifacts( -- includes
    exhibition_id int not null,
    artifact_id int not null,
    added_date timestamp default current_timestamp,

    constraint `exhibition_artifacts_pk` primary key (exhibition_id, artifact_id),
    constraint `exhibition_artifacts_exhibition_id_fk` foreign key (exhibition_id) references exhibitions(id),
    constraint `exhibition_artifacts_artifact_id_fk` foreign key (artifact_id) references artifacts(id)
);

create table if not exists exhibition_artworks(  -- includes
    exhibition_id int not null,
    artwork_id int not null,
    added_date timestamp default current_timestamp,

    constraint `exhibition_artworks_pk` primary key (exhibition_id, artwork_id),
    constraint `exhibition_artworks_exhibition_id_fk` foreign key (exhibition_id) references exhibitions(id),
    constraint `exhibition_artworks_artwork_id_fk` foreign key (artwork_id) references artworks(id)
);

create table if not exists exhibition_staff( -- works_on 
    exhibition_id int not null,
    staff_id int not null,
    role_in_exhibition varchar(50),
    assigned_date timestamp default current_timestamp,

    constraint `exhibition_staff_pk` primary key (exhibition_id, staff_id),
    constraint `exhibition_staff_exhibition_id_fk` foreign key (exhibition_id) references exhibitions(id),
    constraint `exhibition_staff_staff_id_fk` foreign key (staff_id) references staff(id)
);

create table if not exists requests_artifacts( -- to_print // 3D printing requests for artifacts
    request_id int not null,
    artifact_id int not null,
    scale decimal(5,2) default 1.00,
    material_used varchar(50),

    constraint `requests_artifacts_pk` primary key (request_id, artifact_id),
    constraint `requests_artifacts_request_id_fk` foreign key (request_id) references printer_requests(id),
    constraint `requests_artifacts_artifact_id_fk` foreign key (artifact_id) references artifacts(id)
);

create table if not exists requests_artworks( -- to_print // 3D printing requests for artworks
    request_id int not null,
    artwork_id int not null,
    scale decimal(5,2) default 1.00,
    material_used varchar(50),

    constraint `requests_artworks_pk` primary key (request_id, artwork_id),
    constraint `requests_artworks_request_id_fk` foreign key (request_id) references printer_requests(id),
    constraint `requests_artworks_artwork_id_fk` foreign key (artwork_id) references artworks(id)
);

create table if not exists visitors_requests( -- sales tracking / payments 
    visitor_id int not null,
    request_id int not null,
    price decimal(10,2) not null,
    payment_method enum('Cash', 'Credit Card', 'Mobile Payment', 'Bank Transfer') not null,
    currency varchar(3) default 'ILS',
    payment_date timestamp default current_timestamp,
    status enum('Paid', 'Pending', 'Failed') default 'Pending',

    constraint `visitors_requests_pk` primary key (visitor_id, request_id),
    constraint `visitors_requests_visitor_id_fk` foreign key (visitor_id) references visitors(id),
    constraint `visitors_requests_request_id_fk` foreign key (request_id) references printer_requests(id)
);


-- Insert data into tables --

--  Artists, artifacts, and artworks data were generated using AI :)
--  based on real historical contexts related to Jerusalem and Al-Aqsa, 
--  for academic and modeling purposes only, not as an official museum record. 


insert into artifacts 
(name, description, origin, period, material, condition_status, location, image_url, acquisition_date) values
('منبر صلاح الدين', 'نسخة أثرية من منبر المسجد الأقصى', 'القدس', 'العصر الأيوبي', 'خشب محفور', 'Excellent', 'المسجد الأقصى', 'https://d7rm5xoig729r.cloudfront.net/collectiveaccess/images/2/3/7/4/67817_ca_object_representations_media_237408_original.jpg', '1985-03-10'),
('مفتاح باب حطة', 'مفتاح أثري لأحد أبواب المسجد الأقصى', 'القدس', 'العصر العثماني', 'حديد', 'Good', 'باب حطة', '', '1972-06-15'),
('مخطوطة وقفية', 'وثيقة وقف تعود للعصر المملوكي', 'القدس', 'العصر المملوكي', 'ورق جلدي', 'Needs Maintenance', 'متحف الأقصى', '', '1991-09-20'),
('قنديل نحاسي', 'قنديل إنارة قديم من قبة الصخرة', 'القدس', 'العصر العثماني', 'نحاس', 'Good', 'قبة الصخرة', '', '1988-12-01'),
('باب خشبي مزخرف', 'جزء من باب أثري', 'القدس', 'العصر الأموي', 'خشب', 'Excellent', 'المتحف الإسلامي', '', '1975-05-11'),
('عملة أموية', 'دينار أموي ضُرب في القدس', 'القدس', 'العصر الأموي', 'ذهب', 'Excellent', 'المتحف الإسلامي','', '1999-07-07'),
('بلاطة فسيفساء', 'فسيفساء من ساحات الأقصى', 'القدس', 'العصر البيزنطي', 'حجر', 'Good', 'ساحات الأقصى', '', '1980-02-02'),
('شمعدان أثري', 'شمعدان نحاسي قديم', 'القدس', 'العصر المملوكي', 'نحاس', 'Needs Maintenance', 'المتحف الإسلامي', '', '1993-10-18');


insert into artists 
(first_name, last_name, email, bio) values
('كمال', 'بلاطة', 'kamal.balata@example.com', 'فنان تشكيلي فلسطيني'),
('تمام', 'الأكحل', 'tammam.alakhal@example.com', 'فنانة فلسطينية معاصرة'),
('سليمان', 'منصور', 'suleiman.mansour@example.com', 'أحد رواد الفن الفلسطيني'),
('نبيل', 'عناني', 'nabil.annani@example.com', 'فنان تشكيلي من القدس'),
('إسماعيل', 'شموط', 'ismail.shamout@example.com', 'فنان فلسطيني بارز');


insert into artworks 
(title, description, artwork_type, creation_year, location, image_url, artist_id) values
('العودة إلى الوطن', 'لوحة تعبر عن حق العودة', 'Painting', 1975, 'متحف الفن الحديث - رام الله', '', 1),
('الكرامة', 'لوحة تجسد نضال الشعب الفلسطيني', 'Painting', 1982, 'متحف الفن الحديث - رام الله', '', 2),
('القدس في القلب', 'لوحة تعبر عن حب القدس', 'Painting', 1990, 'متحف الفن الحديث - رام الله', '', 3),
('صمود الأرض', 'لوحة تعكس صمود الفلسطينيين', 'Painting', 1985, 'متحف الفن الحديث - رام الله', '', 4),
('حكايات من فلسطين', 'لوحة تحكي قصص الفلسطينيين', 'Painting', 1995, 'متحف الفن الحديث - رام الله', '', 5);


insert into staff 
(first_name, last_name, role, email, hire_date) values
('أحمد', 'نصر', 'Admin', 'admin@qudus.ps', '2018-01-01'),
('ليلى', 'الخالدي', 'Curator', 'curator@qudus.ps', '2019-03-15'),
('محمود', 'نصّار', 'Guide', 'guide@qudus.ps', '2020-06-10'),
('سارة', 'القدسي', 'Designer', 'designer@qudus.ps', '2021-09-01'),
('يوسف', 'التميمي', 'Security', 'security@qudus.ps', '2017-11-20'),
('نور', 'البرغوثي', 'Volunteer', 'volunteer@qudus.ps', '2022-02-05');


insert into visitors
(first_name, last_name, email) values
('محمد', 'البرغوثي', 'm.barghouti@mail.com'),
('آمنة', 'الرجبي', 'a.rajabi@mail.com'),
('خالد', 'هاني', 'k.hani@mail.com'),
('هند', 'حيدر', 'h.hader@mail.com'),
('رامي', 'البديري', 'r.budairi@mail.com');


insert into exhibitions
(title, start_date, end_date, description, status, manager_id) values
('معرض المسجد الأقصى عبر العصور', '2024-01-10', '2024-03-30', 'يعرض تطور المسجد الأقصى تاريخياً', 'Past', 2),
('القدس في الفن التشكيلي', '2024-04-05', '2024-06-20', 'لوحات فنية مستوحاة من القدس', 'Past', 2),
('تحف إسلامية من الأقصى', '2024-07-01', '2024-09-15', 'مقتنيات أثرية نادرة من المسجد الأقصى', 'Ongoing', 2),
('قبة الصخرة: رمز ومعنى', '2024-10-01', '2024-12-31', 'معرض متخصص بقبة الصخرة', 'Upcoming', 2),
('القدس في الذاكرة', '2025-01-15', '2025-04-01', 'معرض تفاعلي يوثق الذاكرة المقدسية', 'Upcoming', 2);


insert into visits
(visitor_id, exhibition_id, feedback, rating) values
(1, 1, 'معرض ثري ومؤثر جداً', 5),
(2, 1, 'تنظيم ممتاز ومحتوى عميق', 4),
(3, 2, 'اللوحات معبرة جداً', 5),
(4, 3, 'التحف نادرة وتستحق الزيارة', 4),
(5, 3, 'تجربة رائعة', 5),
(1, 2, 'أحببت تنوع الأعمال الفنية', 4);


insert into exhibition_artifacts
(exhibition_id, artifact_id) values
(1, 1),
(1, 2),
(1, 3),
(3, 4),
(3, 5),
(3, 6),
(4, 2);


insert into exhibition_artworks
(exhibition_id, artwork_id) values
(2, 1),
(2, 2),
(2, 3),
(5, 4),
(5, 5),
(4, 3);


insert into exhibition_staff
(exhibition_id, staff_id, role_in_exhibition) values
(1, 2, 'Curator'),
(1, 3, 'Guide'),
(2, 2, 'Curator'),
(2, 4, 'Designer'),
(3, 5, 'Security'),
(5, 3, 'Guide');


insert into printer_requests
(request_type, status, file_path, designer_id) values
('3D Model - منبر صلاح الدين', 'Approved', '/prints/minbar.stl', 4),
('Replica - قبة الصخرة', 'Printed', '/prints/dome.obj', 4),
('Mini Artifact - قنديل نحاسي', 'Delivered', '/prints/lamp.stl', 4),
('3D Artwork - القدس في القلب', 'Approved', '/prints/jerusalem_art.stl', 4),
('Educational Model - بلاطة فسيفساء', 'Pending', '/prints/mosaic.stl', 4);


insert into requests_artifacts
(request_id, artifact_id, scale, material_used) values
(1, 1, 0.50, 'PLA'),
(2, 4, 0.30, 'Resin'),
(3, 4, 0.40, 'PLA'),
(5, 7, 0.60, 'PLA'),
(2, 2, 0.25, 'Resin');


insert into requests_artworks
(request_id, artwork_id, scale, material_used) values
(4, 1, 0.40, 'PLA'),
(4, 3, 0.35, 'Resin'),
(2, 5, 0.50, 'PLA'),
(1, 2, 0.30, 'PLA'),
(5, 4, 0.45, 'PLA');


insert into visitors_requests
(visitor_id, request_id, price, payment_method, status) values
(1, 1, 120.00, 'Cash', 'Paid'),
(2, 2, 200.00, 'Credit Card', 'Paid'),
(3, 3, 90.00, 'Mobile Payment', 'Paid'),
(4, 4, 150.00, 'Bank Transfer', 'Pending'),
(5, 5, 110.00, 'Cash', 'Paid');

