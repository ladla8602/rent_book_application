import { Rent } from './../../@shared/models/rent';
import { Component, OnInit, ViewChild } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { Router } from '@angular/router';
import { MatTableDataSource } from '@angular/material/table';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { BookRentService } from 'src/app/services/book-rent.service';
import { AlertDialogBoxComponent } from '../alert-dialog-box/alert-dialog-box.component';

@Component({
  selector: 'app-book-rent-history',
  templateUrl: './book-rent-history.component.html',
  styleUrls: ['./book-rent-history.component.scss']
})
export class BookRentHistoryComponent implements OnInit {

  dataSource: any = new MatTableDataSource();
  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild(MatSort) sort: MatSort;
  displayedColumns: string[] = ['id', 'bookName', 'bookAuthor', 'bookPrice', 'bookRentedOn', 'action'];

  constructor(
    private rentBookService: BookRentService,
    public dialog: MatDialog) { }

  ngOnInit(): void {
    this.loadRentList();
  }

  loadRentList() {
    this.rentBookService.listRentedBook().subscribe(
      (response: any) => {
        const temp = response.result;
        if (temp.length !== 0) {
          const data = [];
          temp.forEach((element: any) => {
            data.push(Rent.fromRequest(element));
          });
          this.dataSource = new MatTableDataSource(data);
          setTimeout(() => {
            this.dataSource.paginator = this.paginator;
            this.dataSource.sort = this.sort;
          });
        }
      },
      (error) => {
        console.log(error);
      }
    );
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

  returnBook(bookId: string) {
    const formData = new FormData();
    formData.append('_method', 'PUT');

    this.rentBookService.returnBook(formData, bookId).subscribe(
      (response: any) => {
        this.loadRentList();
        this.openAlertDialog(response.message, 'success');
      },
      (error) => {
        this.openAlertDialog(error, 'failed');
      }
    );
  }

  printInvoice(id: number) {
    this.rentBookService.getInvoice(id);
  }
}
