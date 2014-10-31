DELETE FROM OpenStackRelease;
ALTER TABLE OpenStackRelease AUTO_INCREMENT = 1;

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Austin','2010.1','2010-11-21 00:00','','Deprecated');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Bexar','2011.1','2011-02-03 00:00','','Deprecated');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Cactus','2011.2','2011-05-15 00:00','','Deprecated');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Diablo','2011.3','2011-09-22 00:00','','EOL');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Essex','2012.1','2012-05-05 00:00','','EOL');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Folsom','2012.2','2012-09-27 00:00','','EOL');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Grizzly','2013.1','2013-05-04 00:00','','SecuritySupported');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Havana','2013.2','2013-10-17 00:00','','Current');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Icehouse','2014.1','2014-04-17 00:00','','Current');

INSERT INTO `OpenStackRelease`
(`Created`,`LastEdited`,`Name`,`ReleaseNumber`,`ReleaseDate`,`ReleaseNotesUrl`,`Status`)
VALUES
(now(),now(),'Trunk','YYYY.N','1970-01-01 00:00','','UnderDevelopment');


-- components 
DELETE FROM OpenStackComponent;
ALTER TABLE OpenStackComponent AUTO_INCREMENT = 1;

INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Dashboard','Horizon','Provides a web-based self-service portal to interact with underlying OpenStack services, such as launching an instance, assigning IP addresses and configuring access controls.'
,0,0);

INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Compute','Nova','Manages the lifecycle of compute instances in an OpenStack environment. Responsibilities include spawning, scheduling and decomissioning of machines on demand.'
,1,1);

INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Networking','Neutron','Enables network connectivity as a service for other OpenStack services, such as OpenStack Compute. Provides an API for users to define networks and the attachments into them. Has a pluggable architecture that supports many popular networking vendors and technologies.'
,1,1);

INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Object Storage','Swift','Stores and retrieves arbitrary unstructured data objects via a RESTful, HTTP based API. It is highly fault tolerant with its data replication and scale out architecture. Its implementation is not like a file server with mountable directories.'
,1,1);

INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Block Storage','Cinder','Provides persistent block storage to running instances. Its pluggable driver architecture facilitates the creation and management of block storage devices.'
,1,1);


INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Identity','Keystone','Provides an authentication and authorization service for other OpenStack services. Provides a catalog of endpoints for all OpenStack services.'
,1,1);

INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Image Service','Glance','Stores and retrieves virtual machine disk images. OpenStack Compute makes use of this during instance provisioning.'
,1,0);

INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Telemetry','Ceilometer','Monitors and meters the OpenStack cloud for billing, benchmarking, scalability, and statistical purposes.'
,1,0);

INSERT INTO `OpenStackComponent`
(`Created`,`LastEdited`,`Name`,`CodeName`,`Description`,`SupportsVersioning`,`SupportsExtensions`)
VALUES
(now(),now(),'Orchestration','Heat',
'Orchestrates multiple composite cloud applications by using either the native HOT template format or the AWS CloudFormation template format, through both an OpenStack-native REST API and a CloudFormation-compatible Query API.'
,1,0);

-- supported components by release

DELETE FROM `OpenStackRelease_OpenStackComponents`;
ALTER TABLE `OpenStackRelease_OpenStackComponents` AUTO_INCREMENT = 1;

-- AUSTIN (Nova, Swift)

select @austin_id := ID from OpenStackRelease where Name='Austin';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @austin_id, ID
FROM   OpenStackComponent
WHERE  CodeName IN ('Nova','Swift');


-- Bexar ('Nova','Swift','Glance')

select @bexar_id := ID from OpenStackRelease where Name='Bexar';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @bexar_id, ID
FROM   OpenStackComponent
WHERE  CodeName IN ('Nova','Swift','Glance');

-- Cactus ('Nova','Swift','Glance')

select @cactus_id := ID from OpenStackRelease where Name='Cactus';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @cactus_id, ID
FROM   OpenStackComponent
WHERE  CodeName IN ('Nova','Swift','Glance');


-- Diablo ('Nova','Swift','Glance')

select @diablo_id := ID from OpenStackRelease where Name='Diablo';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @diablo_id, ID
FROM   OpenStackComponent
WHERE  CodeName IN ('Nova','Swift','Glance');

-- ESSEX (Nova, Glance, Swift, Horizon, Keystone)
select @essex_id := ID from OpenStackRelease where Name='Essex';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @essex_id, ID
FROM   OpenStackComponent
WHERE  CodeName IN ('Nova','Swift','Glance','Horizon','Keystone');

-- FOLSOM (Nova, Glance, Swift, Horizon, Keystone, Neutron, Cinder)
select @folsom_id := ID from OpenStackRelease where Name='Folsom';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @folsom_id, ID
FROM   OpenStackComponent
WHERE  CodeName IN ('Nova','Swift','Glance','Horizon','Keystone','Neutron','Cinder');

--  Grizzly (Nova, Glance, Swift, Horizon, Keystone, Neutron, Cinder) EX Ceilometer. Heat
select @grizzly_id := ID from OpenStackRelease where Name='Grizzly';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @grizzly_id, ID
FROM   OpenStackComponent
WHERE  CodeName IN ('Nova','Swift','Glance','Horizon','Keystone','Neutron','Cinder','Ceilometer','Heat');

-- HAVANA ( 	Nova, Glance, Swift, Horizon, Keystone, Neutron, Cinder)
select @havana_id := ID from OpenStackRelease where Name='Havana';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @havana_id, ID
FROM   OpenStackComponent
WHERE  CodeName IN ('Nova','Swift','Glance','Horizon','Keystone','Neutron','Cinder','Heat','Ceilometer');

-- ICEHOUSE (ALL)
select @icehouse_id := ID from OpenStackRelease where Name='Icehouse';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @icehouse_id, ID
FROM   OpenStackComponent;

-- TRUNK (ALL)
select @trunk_id := ID from OpenStackRelease where Name='Trunk';

INSERT INTO `OpenStackRelease_OpenStackComponents` (`OpenStackReleaseID`,`OpenStackComponentID`)
SELECT @trunk_id, ID
FROM   OpenStackComponent;

-- VERSIONS
-- api version per component
DELETE FROM OpenStackApiVersion;
ALTER TABLE OpenStackApiVersion AUTO_INCREMENT = 1;

-- HORIZON
SELECT @horizon_id := ID from OpenStackComponent where CodeName='Horizon';
-- N/A

-- Nova
SELECT @nova_id := ID from OpenStackComponent where CodeName='Nova';

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.0','Deprecated',@nova_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.1','Deprecated',@nova_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.0','Current',@nova_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v3.0','Current',@nova_id);

-- Neutron

SELECT @neutron_id := ID from OpenStackComponent where CodeName='Neutron';

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.0','Deprecated',@neutron_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.1','Deprecated',@neutron_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.0','Current',@neutron_id);

-- Swift

SELECT @swift_id := ID from OpenStackComponent where CodeName='Swift';
INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.0','Current',@swift_id);


SELECT @swift_id := ID from OpenStackComponent where CodeName='Swift';
INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.0','Current',@swift_id);

-- Cinder
SELECT @cinder_id := ID from OpenStackComponent where CodeName='Cinder';
INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.0','Current',@cinder_id);

SELECT @cinder_id := ID from OpenStackComponent where CodeName='Cinder';
INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.0','Current',@cinder_id);

-- Keystone

SELECT @keystone_id := ID from OpenStackComponent where CodeName='Keystone';

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.0','Deprecated',@keystone_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.0','Current',@keystone_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v3.0','Current',@keystone_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v3.1','Current',@keystone_id);

-- Glance

SELECT @glance_id := ID from OpenStackComponent where CodeName='Glance';

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.0','Current',@glance_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.0','Current',@glance_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.1','Current',@glance_id);


INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.2','Current',@glance_id);

-- Ceilometer

SELECT @ceilometer_id := ID from OpenStackComponent where CodeName='Ceilometer';

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.0','Current',@ceilometer_id);

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v2.0','Current',@ceilometer_id);

-- Heat

SELECT @heat_id := ID from OpenStackComponent where CodeName='Heat';

INSERT INTO `OpenStackApiVersion`
(`Created`,`LastEdited`,`Version`,`Status`,`OpenStackComponentID`)
VALUES
(now(),now(),'v1.0','Current',@heat_id);


-- versions supported by release and component
DELETE FROM OpenStackReleaseSupportedApiVersion;
ALTER TABLE OpenStackReleaseSupportedApiVersion AUTO_INCREMENT = 1;

select @austin_id := ID from OpenStackRelease where Name='Austin';
select @cactus_id := ID from OpenStackRelease where Name='Cactus';
select @bexar_id := ID from OpenStackRelease where Name='Bexar';
select @diablo_id := ID from OpenStackRelease where Name='Diablo';
select @essex_id := ID from OpenStackRelease where Name='Essex';
select @folsom_id := ID from OpenStackRelease where Name='Folsom';
select @grizzly_id := ID from OpenStackRelease where Name='Grizzly';
select @havana_id := ID from OpenStackRelease where Name='Havana';
select @icehouse_id := ID from OpenStackRelease where Name='Icehouse';
select @trunk_id := ID from OpenStackRelease where Name='Trunk';

SELECT @horizon_id := ID from OpenStackComponent where CodeName='Horizon';
SELECT @nova_id := ID from OpenStackComponent where CodeName='Nova';
SELECT @neutron_id := ID from OpenStackComponent where CodeName='Neutron';
SELECT @swift_id := ID from OpenStackComponent where CodeName='Swift';
SELECT @cinder_id := ID from OpenStackComponent where CodeName='Cinder';
SELECT @keystone_id := ID from OpenStackComponent where CodeName='Keystone';
SELECT @glance_id := ID from OpenStackComponent where CodeName='Glance';
SELECT @ceilometer_id := ID from OpenStackComponent where CodeName='Ceilometer';
SELECT @heat_id := ID from OpenStackComponent where CodeName='Heat';

-- Austin
INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @austin_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @nova_id;


INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID, @austin_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

-- Cactus
INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @cactus_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @cactus_id from OpenStackApiVersion
WHERE Version ='v1.1' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID, @cactus_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID, @cactus_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

-- Bexar
INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @bexar_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @bexar_id from OpenStackApiVersion
WHERE Version ='v1.1' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID, @bexar_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID, @bexar_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

-- Diablo
INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @diablo_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @diablo_id from OpenStackApiVersion
WHERE Version ='v1.1' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID, @diablo_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID, @diablo_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

-- Essex

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @essex_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@essex_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@essex_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@essex_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @keystone_id;

-- Folsom

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @folsom_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@folsom_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@folsom_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@folsom_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@folsom_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@folsom_id from OpenStackApiVersion
WHERE Version ='v3.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@neutron_id,ID,@folsom_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @neutron_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@folsom_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @cinder_id;

-- grizzly_id

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @grizzly_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v3.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@neutron_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @neutron_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @cinder_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @cinder_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@ceilometer_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @ceilometer_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@ceilometer_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @ceilometer_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@heat_id,ID,@grizzly_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @heat_id;

-- no versioning for horizon
INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
VALUES(now(),now(),@horizon_id,0, @grizzly_id);

-- Havana

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @havana_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @havana_id from OpenStackApiVersion
WHERE Version ='v3.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v3.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@neutron_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @neutron_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @cinder_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @cinder_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@ceilometer_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @ceilometer_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@heat_id,ID,@havana_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @heat_id;

-- no versioning for horizon
INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
VALUES(now(),now(),@horizon_id,0, @havana_id);

-- Trunk


INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @trunk_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @trunk_id from OpenStackApiVersion
WHERE Version ='v3.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v3.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@neutron_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @neutron_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @cinder_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @cinder_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@ceilometer_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @ceilometer_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@heat_id,ID,@trunk_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @heat_id;

-- no versioning for horizon
INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
VALUES(now(),now(),@horizon_id,0, @trunk_id);

-- icehouse

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @icehouse_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@nova_id,ID, @icehouse_id from OpenStackApiVersion
WHERE Version ='v3.0' AND OpenStackComponentID = @nova_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@glance_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @glance_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@swift_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @swift_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@keystone_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v3.0' AND OpenStackComponentID = @keystone_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@neutron_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @neutron_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @cinder_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@cinder_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @cinder_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@ceilometer_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v2.0' AND OpenStackComponentID = @ceilometer_id;

INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
SELECT now(),now(),@heat_id,ID,@icehouse_id from OpenStackApiVersion
WHERE Version ='v1.0' AND OpenStackComponentID = @heat_id;

-- no versioning for horizon
INSERT INTO `OpenStackReleaseSupportedApiVersion`
(`Created`,`LastEdited`,`OpenStackComponentID`,`ApiVersionID`,`ReleaseID`)
VALUES(now(),now(),@horizon_id,0, @icehouse_id);
