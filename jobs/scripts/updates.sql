update JobPage_Live set active =1;
update JobPage set active =1;
update JobPage_versions set active =1;

update JobPage set ExpirationDate = DATE_ADD(JobPostedDate, INTERVAL 2 MONTH)
where JobPage.JobPostedDate IS NOT NULL;

update JobPage_Live set ExpirationDate = DATE_ADD(JobPostedDate, INTERVAL 2 MONTH)
where JobPage_Live.JobPostedDate IS NOT NULL;

update JobPage_versions set ExpirationDate = DATE_ADD(JobPostedDate, INTERVAL 2 MONTH)
where JobPage_versions.JobPostedDate IS NOT NULL;