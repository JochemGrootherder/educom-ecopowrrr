# educom-ecopowrrr
This repository contains the design diagrams and source code for the educom ecopowrrr case.

##About
Ecopowrrr is an energy supplier which provides 100% renewable power. They achieve this by buying the leftover power from houses with heatpumps and/or solar panels and selling this to their customers.

##Goal of the application
The goal of this application is to read periodic data from smart devices that are provided to Ecopowrrr customers. These devices contain a built-in webserver with an API connection. The data will be collected periodically using a batch-program. This data is then stored in a database.

##Front end
The front end for this application is mocked, but it's supposed to be a mobile app on which customeradvisors are able to connect customers to the backend. In order to connect the customers we need the following (mock) info: personal info, bank, zipcode and housenumber. Using the zipcode and housenumber the adres and geo information will be acquired from [Postcode.tech](Postcode.tech). When this data is put into the database, the device information will be automatically read and stored in the backend.

##Architecture
The image below describes the architecture of the application.
![eco-powrrr-arch-fc59dfc137f80a1064c62df77d5fc82b](https://github.com/user-attachments/assets/ece44708-f646-4011-9677-18778e577e6f)

##Client Devices
Client devices will be mocked by using a generic API server which delivers information based on combinations of zipcodes and housenumbers. When a customeradvisor connects a customer, the data will be written into a central database. Then a message will be sent from the backend to the device which activates the devices. The backend is only allowed to read data from activated devices.

When a customer adds a new power supply, they will automatically get added to the data message.

##Output
The output of the application is put into a spreadsheet. This spreadsheet contains at least three elements
1. An overview of all customers with the total revenue per year per customer and the total of the bought KWH over the period
2. An overview of the total revenue of the current year with a prediction for the rest of the year based on previous results
3. An overview of the total revenue, total winnings and total leftovers per municipality.

##Postcode.tech
This application makes use from [Postcode.tech](Postcode.tech). This is used to request free information about zipcodes. However its only possible to make 10.000 calls per day.
