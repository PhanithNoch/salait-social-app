
Databse schema Design ::


``` User Table
    id int primary key auto_increment,
    name varchar(), not null,
    password varchar(), not null,
    email varchar(), not null, unique
    profile_pic varchar(), nullable 
    bio varchar(), nullable

    ``` Post Table relation with User Table (One to Many)```

    Post Table
    id int primary key auto_increment,
    user_id int foreign key user(id),
    caption varchar(), not null,
    image varchar(), not null,

    

    Like Table 
    id int primary key auto_increment,
    user_id int not null,
    post_id int not null,

    Comment table 
    id int primary key auto_increment,
    user_id int not null,
    post_id int not null,
    text varchar(), not null
```
<!-- Relationship -->