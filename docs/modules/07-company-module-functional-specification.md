\# LeadPilot AI – Company Module Functional Specification



\## 1. Purpose



The Company Module stores and manages prospect/customer company records.



Companies are the foundation of LeadPilot AI. Contacts, leads, tasks, notes, campaigns, AI analysis, and future projects will be connected to companies.



\## 2. Business Objective



The Company Module helps users:



\- Store prospect company information

\- Track potential client organizations

\- Connect companies with contacts and leads

\- Identify business opportunities

\- Prepare for outreach

\- Run AI company analysis

\- Maintain CRM history



\## 3. Core Features



\### Company List



\- View all companies

\- Search by name, website, email, phone, city, country

\- Filter by status, industry, company size, source

\- Pagination

\- Export

\- Bulk actions



\### Add Company



Users can manually add a company.



\### Edit Company



Users can update company details.



\### Delete Company



Companies should use soft delete.



\### Company Details



Each company will have a detailed profile page.



Tabs:



\- Overview

\- Contacts

\- Leads

\- Notes

\- Tasks

\- Activities

\- AI Insights



\## 4. Company Fields



Required:



\- Company Name

\- Status



Optional:



\- Legal Name

\- Website

\- Domain

\- Email

\- Phone

\- Industry

\- Company Size

\- Country

\- State

\- City

\- Address

\- LinkedIn URL

\- Facebook URL

\- Twitter URL

\- Source

\- Notes



System Fields:



\- id

\- uuid

\- tenant\_id

\- created\_by

\- updated\_by

\- deleted\_by

\- created\_at

\- updated\_at

\- deleted\_at



\## 5. Company Statuses



Default statuses:



\- prospect

\- qualified

\- customer

\- inactive

\- blacklisted



\## 6. User Workflow



Dashboard  

→ Companies  

→ Add Company  

→ Save  

→ Company Profile  

→ Add Contact  

→ Add Lead  

→ Follow Up  

→ Convert to Customer



\## 7. Permissions



Required permissions:



\- view companies

\- create companies

\- edit companies

\- delete companies

\- export companies

\- import companies



\## 8. Database Table



Table name:



```text

companies

