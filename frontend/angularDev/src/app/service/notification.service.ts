import { Injectable } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {

  constructor(private toaster: ToastrService) { }

  /**
   * SHOW SUCCESS
   * @param message
   * @param title
   * @return object
   */
  showSuccess(message: string | undefined, title: string | undefined){
    this.toaster.success(message, title)
  }

  /**
   * SHOW ERROR
   * @param message
   * @param title
   * @return object
   */
  showError(message: string | undefined, title: string | undefined){
    this.toaster.error(message, title)
  }

  /**
   * SHOW INFO
   * @param message
   * @param title
   * @return object
   */
  showInfo(message: string | undefined, title: string | undefined){
    this.toaster.info(message, title)
  }

  /**
   * SHOW WARNING
   * @param message
   * @param title
   * @return object
   */
  showWarning(message: string | undefined, title: string | undefined){
    this.toaster.warning(message, title)
  }
}
