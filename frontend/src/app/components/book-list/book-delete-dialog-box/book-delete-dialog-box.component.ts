import { BookDeleteService } from './../../../services/book-delete.service';
import { Component, OnInit, Inject } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef, MatDialog } from '@angular/material/dialog';
import { AlertDialogBoxComponent } from '../../alert-dialog-box/alert-dialog-box.component';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
interface DialogData {
    bookId: number;
    title: string;
    message: string;
}

@Component({
  selector: 'app-book-delete-dialog-box',
  templateUrl: './book-delete-dialog-box.component.html',
  styleUrls: ['./book-delete-dialog-box.component.scss']
})
export class BookDeleteDialogBoxComponent implements OnInit {
  public deleteBookForm: FormGroup;
  constructor(public dialogRef: MatDialogRef<BookDeleteDialogBoxComponent>,
              @Inject(MAT_DIALOG_DATA) public data: DialogData,
              public dialog: MatDialog,
              private formBuilder: FormBuilder,
              private deleteBookService: BookDeleteService) { }

  ngOnInit(): void {
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


  close() {
    this.dialogRef.close();
  }

  onSubmit() {
    const formData = new FormData();
    formData.append('_method', 'delete');
    this.deleteBookService.deleteBook(this.data.bookId, formData).subscribe(
      (response: any) => {
        if (response.status === 200) {
          this.dialogRef.close();
          this.openAlertDialog('Book Deleted Successfully', 'success');
        } else {
          this.dialogRef.close();
          this.openAlertDialog('Somwthing went wrong', 'failed');
        }
      },
      (error) => {
        this.dialogRef.close();
        this.openAlertDialog(error, 'failed');
      }
    );
  }

}
