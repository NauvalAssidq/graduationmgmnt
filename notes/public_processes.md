# Public User Processes

This document outlines the processes and data flows available to public users in the Graduation Management system.

## 1. View Landing Page & Book List
Displays the latest published graduation book and a paginated list of all published books. Includes a search function for books.

```mermaid
sequenceDiagram
    participant User
    participant PublicController
    participant Database
    User->>PublicController: GET / (Home)
    PublicController->>Database: Fetch latest Published BukuWisuda
    PublicController->>Database: Fetch paginated Published BukuWisuda (w/ search filter)
    Database-->>PublicController: Return Books Data
    PublicController-->>User: Render `landing.blade.php`
```

## 2. Search Alumni (Wisudawan)
Allows users to search for specific graduates by name or student ID (NIM).

```mermaid
sequenceDiagram
    participant User
    participant PublicController
    participant Database
    User->>PublicController: GET /cari-alumni?q={keyword}
    PublicController->>Database: Search Wisudawan by nama or nim
    Database-->>PublicController: Return matching Graduates (w/ BukuWisuda relation)
    PublicController-->>User: Render `search_results.blade.php`
```

## 3. View Book Details
Displays the details of a specific graduation book and the list of graduates in that book.

```mermaid
sequenceDiagram
    participant User
    participant PublicController
    participant Database
    User->>PublicController: GET /buku/{id}
    PublicController->>Database: Fetch graduates for specific book (paginated)
    Database-->>PublicController: Return Book and Graduates Data
    PublicController-->>User: Render `book_detail.blade.php`
```

## 4. View Flipbook
Allows users to view the generated PDF of a graduation book in a flipbook format.

```mermaid
sequenceDiagram
    participant User
    participant PublicController
    participant Storage
    User->>PublicController: GET /buku/{id}/flipbook
    PublicController->>PublicController: Check if book has `file_pdf`
    alt PDF Exists
        PublicController-->>User: Render `flipbook.blade.php` (Loads PDF from Storage)
    else PDF Not Found
        PublicController-->>User: Return 404 Not Found
    end
```
