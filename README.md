# MaintenanceOS

## Overview

MaintenanceOS is a Laravel-based property maintenance management platform that manages the complete lifecycle of maintenance requests for residential or commercial properties. The system enables tenants to submit maintenance requests with supporting media, property managers to review and approve requests, automatically generate work orders, dispatch those work orders to an external contractor management system, and receive secure asynchronous updates as work progresses.

The application was intentionally designed to demonstrate modern backend engineering practices rather than simply CRUD operations. The project emphasizes clean architecture, separation of concerns, asynchronous processing, secure service-to-service communication, testing, authorization, and scalable Laravel design patterns.

The system integrates with a separate **Contractor Dispatch Service**, allowing MaintenanceOS to remain responsible for property management while delegating contractor assignment and execution to an external service.

---

# Project Objectives

The primary objectives of this project are to:

- Demonstrate enterprise Laravel architecture
- Implement secure REST APIs
- Apply Role-Based Access Control (RBAC)
- Build asynchronous workflows using queues
- Integrate multiple services through secure webhooks
- Generate reports automatically
- Send notifications based on domain events
- Schedule recurring maintenance operations
- Maintain clean separation between business logic and HTTP controllers

---

# Architecture

MaintenanceOS follows a layered architecture.

```text
Client

↓

HTTP Controllers

↓

Form Requests

↓

Policies

↓

DTOs (Spatie Laravel Data)

↓

Actions

↓

Services

↓

Models

↓

Database
```

The goal is to keep controllers extremely thin while placing business logic inside reusable Actions and Services.

---

# Technology Stack

## Framework

- Laravel 12

## Authentication

- Laravel Sanctum

## Authorization

- Spatie Laravel Permission

## Database

- SQLite (development)
- MySQL/PostgreSQL compatible

## Queue

- Laravel Queues

## PDF Generation

- Barryvdh DomPDF

## Testing

- Pest PHP

## Media Storage

- Laravel Storage (Local Disk)

---

# Key Features

## Authentication

Users can:

- Register
- Login
- Logout

Authentication is handled through Laravel Sanctum using Personal Access Tokens.

---

## Role-Based Authorization

The application supports multiple user roles including:

- Tenant
- Property Manager
- Administrator

Authorization is enforced using:

- Policies
- Spatie Roles & Permissions

---

## Property Management

Managers can:

- Create Properties
- Update Properties
- Delete Properties
- View Properties

Each Property contains multiple Units.

Each Property belongs to one Manager.

---

## Unit Management

Each Property may contain multiple Units.

A Unit belongs to exactly one Property.

Maintenance Requests are submitted against Units.

---

## Maintenance Requests

Tenants submit maintenance requests containing:

- Title
- Description
- Category
- Priority
- Media Uploads

Every Maintenance Request belongs to:

- Property
- Unit

Media is implemented using a polymorphic relationship, allowing future expansion to other entities without schema changes.

---

## Manager Approval Workflow

After a tenant creates a maintenance request:

1. Manager reviews request
2. Manager approves request
3. Request status changes
4. Work Order is automatically generated
5. Approval Pipeline executes
6. Work Order is dispatched to Contractor Dispatch Service

This entire workflow is encapsulated inside a transactional Action.

---

# Work Orders

A Work Order contains:

- Reference Number
- Scope of Work
- Estimated Cost
- Estimated Hours
- Scheduled Date
- Current Status

Each Work Order belongs to exactly one Maintenance Request.

---

## Work Order Lifecycle

```text
Pending

↓

Approved

↓

Dispatched

↓

Accepted

↓

In Progress

↓

Completed
```

Cancelled work orders may occur at any point before completion.

---

# External Contractor Integration

MaintenanceOS communicates with an external Laravel application called:

**Contractor Dispatch Service**

Responsibilities are intentionally separated.

MaintenanceOS:

- Owns maintenance requests
- Owns work orders
- Owns reporting
- Owns managers

Contractor Dispatch Service:

- Receives work orders
- Assigns contractors
- Tracks contractor progress
- Sends secure webhook updates

---

# Secure Service Communication

Communication between both services is secured using:

- API Keys
- HMAC SHA-256 Signatures
- Timestamp Validation

Every outbound request includes:

- X-API-Key
- X-Timestamp
- X-Signature

The receiving application verifies:

- API Key
- Signature
- Timestamp tolerance

This prevents tampering and replay attacks (with replay protection extensible through event IDs).

---

# Webhooks

MaintenanceOS exposes:

```http
POST /api/v1/webhooks/contractor-status
```

Current payload:

```json
{
    "maintenance_work_order_id": 15,
    "status": "completed"
}
```

The webhook:

- Validates API Key
- Verifies HMAC Signature
- Updates Work Order status
- Fires domain events

---

# Asynchronous Processing

MaintenanceOS minimizes work performed during HTTP requests.

Heavy operations are processed asynchronously.

## Jobs

- ProcessContractorWebhookJob
- GenerateCompletionReportJob

---

## Events

- WorkOrderCompleted

---

## Listeners

- GenerateCompletionReport
- NotifyPropertyManager

This event-driven approach keeps the application loosely coupled and scalable.

---

# PDF Completion Reports

Whenever a Work Order reaches the Completed state:

1. WorkOrderCompleted Event fires
2. Listener dispatches GenerateCompletionReportJob
3. PDF generated
4. Stored locally
5. File path saved to database

Reports include:

- Work Order Reference
- Property
- Unit
- Description
- Completion Timestamp

---

# Notifications

Managers receive notifications when:

- Work Orders are completed
- High-priority Work Orders remain unaccepted for four hours

Notifications are queued automatically.

---

# Scheduled Commands

MaintenanceOS includes two scheduled background commands.

## Hourly

```bash
maintenance:escalate-overdue
```

Purpose:

Escalate High Priority Work Orders that remain in the DISPATCHED state for more than four hours.

---

## Daily

```bash
maintenance:daily-digest
```

Purpose:

Send every Property Manager a maintenance summary containing:

- Pending Requests
- Pending Work Orders
- Dispatched Work Orders
- Accepted Work Orders
- In Progress Work Orders
- Completed Today
- Cancelled Today

---

# Domain Workflow

```text
Tenant

↓

Create Maintenance Request

↓

Manager Reviews

↓

Approve Request

↓

Generate Work Order

↓

Approval Pipeline

↓

Dispatch to Contractor Service

↓

Contractor Accepts

↓

Contractor Starts

↓

Contractor Completes

↓

Secure Webhook

↓

Update Work Order

↓

Fire WorkOrderCompleted Event

↓

Generate PDF Report

↓

Notify Property Manager
```

---

# Project Structure

```
app/

Actions/
Controllers/
DTOs/
Enums/
Events/
Http/
Jobs/
Listeners/
Mail/
Models/
Notifications/
Policies/
Requests/
Resources/
Services/
```

Business logic intentionally lives inside:

- Actions
- Services

Controllers remain thin.

---

# Design Principles

The project follows several architectural principles:

- Single Responsibility Principle
- Separation of Concerns
- Dependency Injection
- Event-Driven Design
- Transactional Business Logic
- Service-Oriented Integration
- Thin Controllers
- Reusable DTOs
- Queue-Based Background Processing

---

# Security

The application implements multiple layers of security.

Authentication

- Laravel Sanctum

Authorization

- Spatie Roles & Permissions
- Laravel Policies

External Integrations

- API Keys
- HMAC Verification
- Timestamp Validation

Validation

- Form Requests
- DTO Validation

---

# API Overview

## Authentication

```
POST /register

POST /login

POST /logout
```

---

## Properties

```
GET

POST

PUT

DELETE
```

---

## Units

```
GET

POST

PUT

DELETE
```

---

## Maintenance Requests

```
GET

POST

PUT

DELETE
```

---

## Approval

```
POST

/api/v1/maintenance-requests/{maintenanceRequest}/approve
```

---

## Work Orders

```
POST

/api/v1/maintenance-requests/{maintenanceRequest}/work-order
```

---

## Webhooks

```
POST

/api/v1/webhooks/contractor-status
```

---

# Running the Project

Install dependencies

```bash
composer install
```

Copy environment

```bash
cp .env.example .env
```

Generate key

```bash
php artisan key:generate
```

Run migrations

```bash
php artisan migrate --seed
```

Start the application

```bash
php artisan serve
```

Run queue worker

```bash
php artisan queue:work
```

Run scheduler

```bash
php artisan schedule:work
```

---

# Environment Variables

```env
CONTRACTOR_API_URL=http://localhost:8002

CONTRACTOR_API_KEY=maintenance-os-secret

CONTRACTOR_WEBHOOK_SECRET=maintenance-os-secret-2026

CONTRACTOR_TIMEOUT=15

CONTRACTOR_RETRY_TIMES=3

CONTRACTOR_SIGNATURE_TOLERANCE=300
```

---

# Testing

The project includes comprehensive automated tests covering:

- Authentication
- Authorization
- Property Management
- Unit Management
- Maintenance Requests
- Work Order Generation
- Approval Workflow
- Contractor Dispatch
- Webhook Processing
- Background Jobs
- Notifications
- Scheduled Commands
- Services
- Actions

Run the test suite with:

```bash
php artisan test
```

or

```bash
./vendor/bin/pest
```

---

# Future Improvements

Potential production enhancements include:

- Webhook replay protection using event IDs
- Advanced audit logging
- Billing and invoicing integration
- Contractor performance analytics
- SMS and push notifications
- Real-time updates using WebSockets
- Multi-tenancy
- Object storage (Amazon S3, Azure Blob Storage)
- Monitoring and observability
- CI/CD deployment pipeline
- Docker containerization
- API versioning strategy

---

# Conclusion

MaintenanceOS demonstrates the implementation of a modern Laravel backend using clean architecture, secure inter-service communication, asynchronous processing, and event-driven design. The system manages the full maintenance lifecycle—from tenant request creation through manager approval, external contractor execution, secure webhook synchronization, automated report generation, and manager notifications—while keeping responsibilities well separated and the codebase maintainable, scalable, and testable.
