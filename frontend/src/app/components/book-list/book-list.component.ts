import { BookRentService } from './../../services/book-rent.service';
import { Book } from './../../@shared/models/book';
import { Component, OnInit, ViewChild } from '@angular/core';
import { BookListService } from 'src/app/services/book-list.service';
import { MatDialog } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { MatTableDataSource } from '@angular/material/table';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { AuthenticationService } from 'src/app/@shared/services/authentication.service';
import { User, Role } from 'src/app/@shared/models';
import { BookDeleteDialogBoxComponent } from './book-delete-dialog-box/book-delete-dialog-box.component';
import { AlertDialogBoxComponent } from '../alert-dialog-box/alert-dialog-box.component';
@Component({
  selector: 'app-book-list',
  templateUrl: './book-list.component.html',
  styleUrls: ['./book-list.component.scss']
})
export class BookListComponent implements OnInit {
  currentUser: User;
  dataSource: any = new MatTableDataSource();
  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  displayedColumns: string[] = ['bookName', 'bookAuthor', 'bookPrice', 'bookPublishOn', 'action'];

  constructor(
    private bookListService: BookListService,
    private router: Router,
    public dialog: MatDialog,
    private authService: AuthenticationService,
    private rentBookService: BookRentService
  ) {
    this.authService.currentUser.subscribe(x => this.currentUser = x);
  }

  ngOnInit(): void {
    this.loadBookList();
  }

  openDeleteDialog(bookId: number) {
    const dialogRef = this.dialog.open(BookDeleteDialogBoxComponent, {
      width: '600px',
      data: { bookId: bookId, title: 'DELETE', message: 'Do you want to delete this book?' },
      position: {
        top: '196px'
      }
    });
    dialogRef.afterClosed().subscribe(result => {
      this.loadBookList();
    });
  }

  openAlertDialog(message: string, type: string) {
    const dialogRef = this.dialog.open(AlertDialogBoxComponent, {
      width: '600px',
      data: { message, type },
      position: {
        top: '196px'
      }
    });
    dialogRef.afterClosed().subscribe(result => {
    });
  }

  get isAdmin() {
    return this.currentUser && this.currentUser.role === Role.Admin;
  }

  get isRenter() {
    return this.currentUser && this.currentUser.role === Role.Renter;
  }

  loadBookList(sort?: boolean) {

    this.bookListService.getBookList().subscribe((data: any) => {
      const temp = data.result;
      if (temp.length !== 0) {
        const data = [];
        temp.forEach((element: any) => {
          data.push(Book.fromRequest(element));
        });
        this.dataSource = new MatTableDataSource(data);
        setTimeout(() => {
          this.dataSource.paginator = this.paginator;
          this.dataSource.sort = this.sort;
        });
      }
    }, error => {
      console.log(error);
    });
  }

  goToEditPage(book: any) {
    this.router.navigateByUrl('/book-list/edit/' + book.id, { state: book });
  }

  rentBook(bookId: string) {
    const formData = new FormData();
    formData.append('book_id', bookId);

    this.rentBookService.rentBook(formData).subscribe(
      (response: any) => {
        this.loadBookList();
        this.openAlertDialog(response.message, 'success');
      },
      (error) => {
        this.openAlertDialog(error, 'failed');
      }
    );
  }
}
