# LeadPilot Design System – Colors

## Purpose

This document defines the official color system for LeadPilot AI.

All pages, components, buttons, cards, forms, tables, charts, and dashboards must use these colors.

## Brand Colors

| Name | Hex | Usage |
|---|---|---|
| Primary Blue | #2563EB | Main actions, links, active states |
| Primary Hover | #1D4ED8 | Button hover, active interactions |
| Success Green | #16A34A | Success states, positive metrics |
| Warning Amber | #F59E0B | Warnings, pending states |
| Danger Red | #DC2626 | Errors, delete actions |
| Dark Navy | #0F172A | Sidebar, dark surfaces |
| Background | #F8FAFC | Application background |
| Card White | #FFFFFF | Cards and panels |
| Border | #E5E7EB | Borders and separators |
| Text Dark | #111827 | Primary text |
| Text Muted | #6B7280 | Secondary text |

## Status Colors

| Status | Background | Text |
|---|---|---|
| Prospect | #DBEAFE | #1D4ED8 |
| Qualified | #DCFCE7 | #15803D |
| Customer | #ECFDF5 | #047857 |
| Inactive | #F1F5F9 | #475569 |
| Blacklisted | #FEE2E2 | #B91C1C |

## Rules

- Use Primary Blue only for important actions.
- Use Danger Red only for destructive actions.
- Avoid random colors outside this palette.
- Every new component must use these color tokens.
- Dark mode colors will be defined separately in `11-dark-mode.md`.

## CSS Variables

```css
--lp-primary: #2563EB;
--lp-primary-hover: #1D4ED8;
--lp-success: #16A34A;
--lp-warning: #F59E0B;
--lp-danger: #DC2626;
--lp-dark: #0F172A;
--lp-bg: #F8FAFC;
--lp-card: #FFFFFF;
--lp-border: #E5E7EB;
--lp-text: #111827;
--lp-muted: #6B7280;