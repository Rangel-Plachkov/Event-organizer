# Event-organizer
Technical requirements:
```User interace```
```Event -createEvent(),```

DB commands:

```mysqldump -u root  -h localhost -P 3306 Event-organizer > ./init.sql(Dump DB)```

```mysql -u root  -h localhost -P 3306  Event-organizer < ./Migrations/init.sql (Create Tables)  ```