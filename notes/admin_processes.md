# Admin Processes

This document outlines the CRUD operations and management processes available to the Administrator.

## 1. Manage Buku Wisuda (Graduation Books)
Processes for creating and managing graduation books.

```mermaid
flowchart TD
    Start((Admin)) --> Index[View Book List]
    Index --> Create[Add New Book]
    Index --> Edit[Edit Book]
    Index --> Delete[Delete Book]

    Create -->|Fill Form & Upload PDF| Store{Validate}
    Store -- Valid --> SaveDB[(Save to Database)]
    Store -- Invalid --> Create

    Edit -->|Update Form & PDF| Update{Validate}
    Update -- Valid --> UpdateDB[(Update Database)]
    Update -- Invalid --> Edit

    Delete --> RemoveDB[(Delete from Database)]
```

## 2. Manage Wisudawan (Graduates)
Processes to manage individual graduate data, including photo uploads and assignments to a book.

```mermaid
flowchart TD
    Start((Admin)) --> Index[View Graduates List]
    Index --> Create[Add New Graduate]
    Index --> Edit[Edit Graduate]
    Index --> Delete[Delete Graduate]
    Index --> Import[Import CSV Data]

    Create -->|Fill Form, Select Book, Upload Photo| Store{Validate}
    Store -- Valid --> SaveStorage[Save Photo to Storage]
    SaveStorage --> SaveDB[(Save to DB)]
    Store -- Invalid --> Create

    Edit -->|Update Data & Photo| Update{Validate}
    Update -- Valid --> CheckPhoto{New Photo?}
    CheckPhoto -- Yes --> ReplaceStorage[Replace Photo in Storage]
    ReplaceStorage --> UpdateDB[(Update DB)]
    CheckPhoto -- No --> UpdateDB
    Update -- Invalid --> Edit

    Delete --> RemoveStorage[Remove Photo from Storage]
    RemoveStorage --> RemoveDB[(Delete from DB)]
```

## 3. Manage Templates
Processes for managing the layout and styling templates for the generated graduation books.

```mermaid
flowchart TD
    Start((Admin)) --> Index[View Templates List]
    Index --> Create[Create Template]
    Index --> Edit[Edit Template]
    Index --> Delete[Delete Template]

    Create -->|Input HTML, CSS, Layout| Store{Validate}
    Store -- Valid --> SaveDB[(Save to DB)]
    
    Edit -->|Update Layout/Style| Update{Validate}
    Update -- Valid --> UpdateDB[(Update DB)]
```

## 4. Document Generation (Arsip)
Processes related to previewing and generating the final PDF version of the graduation book using the assigned template and populated graduate data.

```mermaid
sequenceDiagram
    participant Admin
    participant ArsipController
    participant Database
    participant Browsershot
    participant Storage

    Admin->>ArsipController: GET /arsip/preview/{id}
    ArsipController->>Database: Fetch Book, Template, and Ordered Graduates
    Database-->>ArsipController: Return Data
    ArsipController-->>Admin: Render `print_book.blade.php` (Preview)

    Admin->>ArsipController: POST /arsip/generate/{id}
    alt PDF Already Exists
        ArsipController->>Storage: Delete existing PDF
        ArsipController->>Database: Set file_pdf = null
        ArsipController-->>Admin: Return Success (Deleted)
    else Generate New PDF
        ArsipController->>Database: Fetch Book, Template, and Ordered Graduates
        ArsipController->>ArsipController: Render HTML View
        ArsipController->>Browsershot: Send HTML to Browsershot
        Browsershot->>Storage: Save rendered PDF to disk
        ArsipController->>Database: Update `file_pdf` path
        ArsipController-->>Admin: Return Success (Generated)
    end
```
