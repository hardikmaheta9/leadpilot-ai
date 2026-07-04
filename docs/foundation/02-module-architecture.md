# LeadPilot AI – Module Architecture

## 1. Purpose

This document defines the main modules of LeadPilot AI and how they will be organized.

The goal is to build the platform as a modular, scalable, secure, and SaaS-ready product.

---

## 2. Core Module Groups

LeadPilot AI will be divided into these main groups:

1. Core System
2. CRM
3. Lead Discovery
4. AI Engine
5. Outreach
6. Reporting & Analytics
7. SaaS & Billing
8. System Administration

---

## 3. Core System Modules

### 3.1 Authentication

Features:

- Login
- Logout
- Forgot password
- Reset password
- Password encryption
- Session management
- Login activity tracking

### 3.2 User Management

Features:

- Create users
- Edit users
- Activate/deactivate users
- Assign roles
- User profile
- Password update

### 3.3 Roles & Permissions

Roles:

- Super Admin
- Admin
- Marketing Manager
- Sales Executive
- Viewer

Permission examples:

- View Dashboard
- Manage Users
- Manage Leads
- Manage Companies
- Manage Contacts
- Manage Settings
- View Reports
- Manage Campaigns
- Use AI Tools

### 3.4 Settings

Settings include:

- Company profile
- SMTP settings
- API keys
- Lead scoring rules
- Notification settings
- Daily report settings
- Branding settings

---

## 4. CRM Modules

### 4.1 Companies

Stores company-level information.

Includes:

- Company name
- Website
- Domain
- Industry
- Country
- City
- Phone
- Email
- Social links
- Technology stack
- Website quality status
- Notes

### 4.2 Contacts

Stores decision makers and contact persons.

Includes:

- Full name
- Job title
- Email
- Phone
- LinkedIn profile
- Company relation
- Contact status
- Decision maker flag

### 4.3 Leads

Stores opportunity records.

Includes:

- Lead title
- Requirement
- Source
- Service category
- Lead score
- Lead quality
- Estimated budget
- Status
- AI summary
- Recommended pitch

### 4.4 Notes

Allows users to add internal notes for leads, companies, and contacts.

### 4.5 Follow-ups

Tracks future actions.

Includes:

- Follow-up date
- Follow-up time
- Type
- Status
- Message
- Assigned user

### 4.6 Tasks

Used for general sales and business development tasks.

### 4.7 Pipeline

Pipeline stages:

1. New
2. Qualified
3. Contacted
4. Follow-up
5. Meeting Scheduled
6. Proposal Sent
7. Negotiation
8. Won
9. Lost
10. Ignored

---

## 5. Lead Discovery Modules

### 5.1 Lead Sources

Manages platforms used for lead discovery.

Examples:

- Google Search
- Bing Search
- Reddit
- RSS
- Job Boards
- Google Maps
- Startup Directories
- Business Directories
- Manual Entry

### 5.2 Keyword Management

Stores keywords used for lead discovery.

Examples:

- looking for website developer
- need mobile app developer
- looking for Shopify developer
- need AI automation expert
- website redesign required

### 5.3 Search Logs

Tracks every automated search.

Includes:

- Keyword
- Source
- Search query
- Total results
- New leads found
- Duplicates found
- Error messages

### 5.4 Duplicate Detection

Prevents duplicate leads using:

- Email
- Domain
- Source URL
- Post URL
- Duplicate hash

### 5.5 Lead Import Engine

Imports leads from:

- Search APIs
- CSV
- Manual entry
- RSS
- External APIs

---

## 6. AI Engine Modules

### 6.1 AI Lead Scoring

Scores leads based on:

- Buying intent
- Budget potential
- Urgency
- Company quality
- Contact quality
- Service fit

### 6.2 AI Company Summary

Generates a short summary of company opportunity.

### 6.3 AI Email Writer

Generates personalized outreach emails.

### 6.4 AI Follow-up Writer

Generates follow-up messages.

### 6.5 AI Website Audit

Analyzes:

- Website speed
- Mobile responsiveness
- SEO quality
- SSL
- Design quality
- Conversion issues
- Missing features

### 6.6 AI Proposal Generator

Generates:

- Project summary
- Scope of work
- Timeline
- Estimated budget
- Suggested proposal email

### 6.7 AI Prompt Manager

Stores and manages reusable AI prompts.

---

## 7. Outreach Modules

### 7.1 Email Templates

Stores reusable templates.

### 7.2 Email Logs

Tracks sent emails.

### 7.3 Campaigns

Creates outreach campaigns.

Campaign statuses:

- Draft
- Active
- Paused
- Completed

### 7.4 Campaign Leads

Tracks each lead inside a campaign.

Statuses:

- Pending
- Sent
- Opened
- Replied
- Bounced
- Failed

### 7.5 Daily Email Report

Sends daily summary of fresh leads.

---

## 8. Reporting & Analytics Modules

### 8.1 Dashboard Analytics

Shows:

- Total leads
- Hot leads
- Warm leads
- Cold leads
- New companies
- Follow-ups due
- Campaign performance
- Source performance

### 8.2 Lead Source Reports

Shows which source generates best leads.

### 8.3 Country Reports

Shows best performing countries.

### 8.4 Service Category Reports

Shows demand by service.

### 8.5 Sales Pipeline Reports

Shows won, lost, and pending opportunities.

---

## 9. SaaS & Billing Modules

These modules are planned for future versions.

### 9.1 Tenant Management

Each company will have isolated data.

### 9.2 Subscription Plans

Plan examples:

- Free
- Starter
- Professional
- Agency
- Enterprise

### 9.3 Billing

Possible payment gateways:

- Razorpay
- Stripe
- PayPal

### 9.4 Usage Limits

Limits may include:

- Leads per month
- Users
- AI credits
- Campaign emails
- Search sources

---

## 10. System Administration Modules

### 10.1 Activity Logs

Tracks user activity.

### 10.2 API Logs

Tracks external API requests and responses.

### 10.3 Cron Logs

Tracks scheduled jobs.

### 10.4 Notifications

Handles system notifications.

### 10.5 Error Logs

Captures application issues.

---

## 11. Module Development Priority

### Phase 1 – Foundation

- Authentication
- Dashboard layout
- Users
- Roles
- Settings

### Phase 2 – CRM

- Companies
- Contacts
- Leads
- Notes
- Follow-ups

### Phase 3 – Lead Discovery

- Sources
- Keywords
- Search logs
- Manual import
- Basic API search

### Phase 4 – AI

- Lead scoring
- Email writer
- Company summary
- Website audit

### Phase 5 – Outreach

- Email templates
- Email logs
- Campaigns
- Daily reports

### Phase 6 – SaaS

- Tenants
- Plans
- Billing
- Usage limits

---

## 12. Design Rule

Every module must follow this structure:

- Routes
- Controller
- Form Request
- Service
- Model
- Policy
- Blade views
- Tests where required

Controllers should stay thin. Business logic should go into services.

---

## 13. Product Rule

Every feature must help the user answer at least one of these questions:

- Who should I contact?
- Why should I contact them?
- What should I say?
- When should I follow up?
- What is the opportunity value?
- How can I convert this lead into a client?
