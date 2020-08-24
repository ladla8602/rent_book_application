import { AlertDialogBoxComponent } from './../alert-dialog-box/alert-dialog-box.component';
import { BookEditService } from './../../services/book-edit.service';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { MatDialog } from '@angular/material/dialog';
@Component({
  selector: 'app-book-edit',
  templateUrl: './book-edit.component.html',
  styleUrls: ['./book-edit.component.scss']
})
export class BookEditComponent implements OnInit {
  public editBookForm: FormGroup;
  public bookData: any;
  public loading = false;

  constructor(private formBuilder: FormBuilder,
              private location: Location,
              private editBookService: BookEditService,
              private dialog: MatDialog) {
    this.bookData = this.location.getState();
  }

  ngOnInit(): void {
    this.editBookForm = this.formBuilder.group({
      book_name: [this.bookData.bookName, Validators.required],
      book_author: [this.bookData.bookAuthor, Validators.required],
      book_price: [this.bookData.bookPrice, Validators.required]
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
      this.location.back();
    });
  }

  onSubmit() {
    this.loading = true;
    // stop here if form is invalid
    if (this.editBookForm.invalid) {
      return;
    }

    const formData = new FormData();
    formData.append('_method', 'put');
    formData.append('book_name', this.editBookForm.get('book_name').value);
    formData.append('book_author', this.editBookForm.get('book_author').value);
    formData.append('book_price', this.editBookForm.get('book_price').value);

    this.editBookService.editBook(this.bookData.id, formData).subscribe(
      (response) => {
        this.loading = false;
        console.log(response);
        this.openAlertDialog('Book Updated Successfully', 'success');
      },
      (error) => {
        this.loading = false;
        console.log(error);
        this.openAlertDialog(error, 'failed');
      }
    );
  }

  back() {
    this.location.back();
  }

}
