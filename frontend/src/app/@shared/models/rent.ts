import { Book } from './book';
export class Rent {
  id: number;
  rentDate: Date;
  bookId: number;
  isReturned: number;
  invoice: string;
  hasInvoice: number;
  returnedDate: Date;
  book: Book;

  static fromRequest(data: any): Rent {
    const rent = new Rent();
    rent.id = data.id;
    rent.bookId = data.book_id;
    rent.rentDate = data.rent_date;
    rent.isReturned = data.is_returned;
    rent.invoice = data.invoice;
    rent.hasInvoice = data.has_invoice;
    rent.returnedDate = data.returned_date;
    rent.book = Book.fromRequest(data.book);
    return rent;
  }
}
