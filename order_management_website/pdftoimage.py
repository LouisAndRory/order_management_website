import sys
import os
from os.path import basename
from zipfile import ZipFile
from pdf2image import convert_from_path

def main():
    args = sys.argv
    order_id = args[1]

    app_path = '/var/www/html/order_management_website/order_management_website/'
    # app_path = '/Users/louis/Projects/order_management_website/order_management_website/'

    pages = convert_from_path(app_path + '/storage/app/order_files/order_' + order_id + '.pdf')
    imageFiles = []
    for idx,page in enumerate(pages):
        filename = app_path + '/storage/app/order_files/' + order_id + '_' + str(idx) + '.jpg'
        page.save(filename, 'JPEG')
        imageFiles.append(filename)

    with ZipFile(app_path + '/storage/app/order_files/' + order_id + '.zip', 'w') as zipObj2:
        for filename in imageFiles:
            zipObj2.write(filename, basename(filename))

if __name__ == '__main__':
    main()
