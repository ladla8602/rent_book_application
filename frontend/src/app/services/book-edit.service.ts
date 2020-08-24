import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BookEditService {

  constructor(private http: HttpClient) { }

  editBook(bookId: number, body: any) {
    const url = `${environment.apiUrl}/update-book/` + bookId;
    return this.http.post(url, body);
  }
}
