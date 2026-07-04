# LeadPilot AI

# Document 05

# User Roles & Permission Matrix

Version: 1.0

---

# Purpose

This document defines:

- User Roles
- Permission Groups
- Access Matrix
- Future SaaS Roles

The system must be Role-Based Access Control (RBAC).

Every user belongs to one or more roles.

Every role contains one or more permissions.

Controllers, menus and APIs must always verify permissions before executing business logic.

---

# Standard Roles

## 1. Super Administrator

Highest level.

Can access everything.

Responsibilities

- System Configuration
- SaaS Configuration
- Subscription Plans
- Global Settings
- AI Configuration
- Billing
- User Management
- Audit Logs
- Tenant Management

Restrictions

None

---

## 2. Tenant Administrator

Company Owner.

Can manage their own organization.

Responsibilities

- Manage Users
- Manage Companies
- Manage Leads
- Manage Campaigns
- View Reports
- SMTP
- AI Settings

Restrictions

Cannot access another tenant.

---

## 3. Marketing Manager

Responsibilities

- Manage Lead Discovery
- Manage Keywords
- Manage Campaigns
- Manage Email Templates
- View Reports
- AI Lead Scoring

Restrictions

Cannot manage users.

---

## 4. Sales Manager

Responsibilities

- Companies
- Contacts
- Leads
- Pipeline
- Meetings
- Tasks
- Reports

Restrictions

Cannot manage system settings.

---

## 5. Sales Executive

Responsibilities

- Assigned Leads
- Follow-ups
- Tasks
- Meetings
- Notes

Restrictions

Cannot delete companies.

Cannot change system settings.

---

## 6. AI Manager

Responsibilities

- Prompt Templates
- AI Configuration
- AI Models
- AI Reports
- AI Costs

Restrictions

Cannot manage billing.

---

## 7. Support Executive

Responsibilities

- Tickets
- Notifications
- Customer Support

Future module.

---

## 8. Finance Manager

Responsibilities

Invoices

Payments

Subscriptions

Revenue Reports

---

## 9. Viewer

Read-only access.

Cannot modify anything.

---

# Permission Groups

Core

Dashboard

Users

Roles

Permissions

Tenant

CRM

Companies

Contacts

Leads

Notes

Tasks

Meetings

Pipeline

Discovery

Search

Keywords

Lead Sources

Search Jobs

Search Results

Campaigns

Email Templates

Email Logs

Campaign Reports

AI

Lead Scoring

Email Generator

Proposal Generator

Website Audit

Prompt Manager

Analytics

Dashboard Reports

Lead Reports

Campaign Reports

Revenue Reports

Settings

SMTP

API Keys

Company Profile

Branding

Logs

Activity Logs

Audit Logs

API Logs

Cron Logs

---

# CRUD Permission Types

Every module should support:

View

Create

Update

Delete

Export

Import

Approve

Assign

Archive

Restore

Example

companies.view

companies.create

companies.update

companies.delete

companies.export

companies.import

---

# Future API Permissions

api.read

api.write

api.delete

webhook.manage

---

# SaaS Permissions

tenant.manage

plan.manage

subscription.manage

payment.manage

invoice.manage

usage.manage

---

# Security Rules

Menu visibility depends on permission.

Routes depend on permission.

Controller depends on permission.

API depends on permission.

Database queries must always be tenant scoped.

---

# Default Role Matrix

| Module | Super | Tenant Admin | Marketing | Sales Manager | Sales Executive | Viewer |
|---------|-------|--------------|------------|---------------|----------------|---------|
| Dashboard | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Companies | ✓ | ✓ | ✓ | ✓ | Assigned | Read |
| Contacts | ✓ | ✓ | ✓ | ✓ | Assigned | Read |
| Leads | ✓ | ✓ | ✓ | ✓ | Assigned | Read |
| Campaigns | ✓ | ✓ | ✓ | View | No | Read |
| AI | ✓ | ✓ | ✓ | View | Limited | No |
| Reports | ✓ | ✓ | ✓ | ✓ | Limited | Read |
| Users | ✓ | ✓ | No | No | No | No |
| Settings | ✓ | ✓ | No | No | No | No |

---

# Future Enterprise Roles

Regional Manager

Branch Manager

Team Leader

Quality Auditor

Business Analyst

External Consultant

Partner

Reseller

API Client

---

# Permission Naming Rules

Always use:

module.action

Examples

companies.create

companies.view

companies.update

companies.delete

leads.assign

campaigns.run

settings.update

Never use numeric permission IDs inside code.

Always check permission name.

---

# Golden Rule

Never trust frontend visibility.

Every controller must verify permission before executing business logic.
