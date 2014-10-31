DELETE FROM GuestOSType;
INSERT INTO `GuestOSType`(`Type`,Created,LastEdited) VALUES 
('Windows',now(),now());

INSERT INTO `GuestOSType`(`Type`,Created,LastEdited) VALUES 
('Linux',now(),now());

DELETE FROM HyperVisorType;
INSERT INTO `HyperVisorType`
(`Created`,`LastEdited`,`Type`)
VALUES (now(),now(),'KVM');

INSERT INTO `HyperVisorType`
(`Created`,`LastEdited`,`Type`)
VALUES (now(),now(),'QEMU');

INSERT INTO `HyperVisorType`
(`Created`,`LastEdited`,`Type`)
VALUES (now(),now(),'LXC');

INSERT INTO `HyperVisorType`
(`Created`,`LastEdited`,`Type`)
VALUES (now(),now(),'ESXi');

INSERT INTO `HyperVisorType`
(`Created`,`LastEdited`,`Type`)
VALUES (now(),now(),'Hyper-V');

INSERT INTO `HyperVisorType`
(`Created`,`LastEdited`,`Type`)
VALUES (now(),now(),'Docker');

INSERT INTO `HyperVisorType`
(`Created`,`LastEdited`,`Type`)
VALUES (now(),now(),'Xen');

DELETE FROM MarketPlaceVideoType;

INSERT INTO `MarketPlaceVideoType`
(`Created`,`LastEdited`,`Type`,`MaxTotalVideoTime`,`Title`,`Description`)
VALUES
(now(), now(),'Overview',90,'90 second Video Overview (optional)','If you''d like to include a video about this specific product, it must be 90 seconds or less an emphasis on educating customers about the OpenStack-related capabilities of your product. In the future, we''ll provide more detailed guidelines. Paste the YouTubeID from your video');

INSERT INTO `MarketPlaceVideoType`
(`Created`,`LastEdited`,`Type`,`MaxTotalVideoTime`,`Title`,`Description`)
VALUES
(now(), now(),'Demo',300,'5 minute Video Demo (optional)','The Video Demo should be a walk through of your product, up to 5 minutes in length, showing off the key capabilites in an education way, with an emphasis on the OpenStack related components. In the future, we''ll provide more detailed guidelines. Paste the YouTubeID from your video');


DELETE FROM `SupportChannelType`;
INSERT INTO `SupportChannelType`
(`Created`,`LastEdited`,`Type`,`IconID`)
VALUES
(now(),now(),'Email',0);

INSERT INTO `SupportChannelType`
(`Created`,`LastEdited`,`Type`,`IconID`)
VALUES
(now(),now(),'Community',0);


INSERT INTO `SupportChannelType`
(`Created`,`LastEdited`,`Type`,`IconID`)
VALUES
(now(),now(),'Forum',0);

INSERT INTO `SupportChannelType`
(`Created`,`LastEdited`,`Type`,`IconID`)
VALUES
(now(),now(),'Phone',0);

INSERT INTO `SupportChannelType`
(`Created`,`LastEdited`,`Type`,`IconID`)
VALUES
(now(),now(),'Chat',0);

INSERT INTO `SupportChannelType`
(`Created`,`LastEdited`,`Type`,`IconID`)
VALUES
(now(),now(),'Other',0);

DELETE FROM `Region`;

INSERT INTO `Region`
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'South America');

INSERT INTO `Region`
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'Central America');

INSERT INTO `Region`
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'North America');

INSERT INTO `Region`
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'Europe');


INSERT INTO `Region`
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'Middle East');

INSERT INTO `Region`
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'Asia Pacific');

DELETE FROM PricingSchemaType;

INSERT INTO PricingSchemaType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(), now(),'Per minute');

INSERT INTO PricingSchemaType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(), now(),'Hourly');

INSERT INTO PricingSchemaType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(), now(),'Monthly');

INSERT INTO PricingSchemaType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(), now(),'Long-term Contract');


DELETE FROM SpokenLanguage;

INSERT INTO SpokenLanguage
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'English');

INSERT INTO SpokenLanguage
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'German');

INSERT INTO SpokenLanguage
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'French');

INSERT INTO SpokenLanguage
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'Spanish');

INSERT INTO SpokenLanguage
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'Hindi');

INSERT INTO SpokenLanguage
(`Created`,`LastEdited`,`Name`)
VALUES
(now(),now(),'Portuguese');

DELETE FROM ConsultantServiceOfferedType;

INSERT INTO ConsultantServiceOfferedType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Cloud Strategy');

INSERT INTO ConsultantServiceOfferedType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Proof of Concept');

INSERT INTO ConsultantServiceOfferedType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Operations');

INSERT INTO ConsultantServiceOfferedType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'System Integration');

INSERT INTO ConsultantServiceOfferedType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Support');

INSERT INTO ConsultantServiceOfferedType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Feature Development (adding features to OpenStack)');

DELETE FROM ConfigurationManagementType;

INSERT INTO ConfigurationManagementType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Chef');

INSERT INTO ConfigurationManagementType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Puppet');

INSERT INTO ConfigurationManagementType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Salt');

INSERT INTO ConfigurationManagementType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'Ansible');

INSERT INTO ConfigurationManagementType
(`Created`,`LastEdited`,`Type`)
VALUES
(now(),now(),'CFEngine');
