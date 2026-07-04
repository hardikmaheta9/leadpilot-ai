# LeadPilot AI – Database Architecture

Version: 1.0  
Architecture Type: Multi-Tenant SaaS Ready  
Database: MySQL  
Framework: Laravel 10  

---

## 1. Purpose

This document defines the database architecture for LeadPilot AI.

The database must support:

- CRM
- Lead discovery
- AI scoring
- Campaigns
- Reports
- SaaS subscriptions
- Multi-company usage
- Audit logging
- Future API and mobile app support

---

## 2. Architecture Decision

LeadPilot AI will be designed as a multi-tenant platform from Day 1.

This means every customer company using the software will be treated as a tenant.

Initially, WebApp Infoway will be the first tenant.

---

## 3. Tenant Strategy

Each major business table will include:

```sql
tenant_id BIGINT UNSIGNED NULL
