import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BookAddService {

  constructor(private http: HttpClient) { }

  addNewBook(body: any) {
    return this.http.post(`${environment.apiUrl}/add-new-book`, body);
  }
}
