
import re


class logReader:

    def __init__(self, files, errors):

        self.file = open(files, 'r', encoding="Latin-1")

        # errors dictionary
        self.errors = {}
        for er in errors:
            self.errors[er[0]] = []

        # loop through the log and check all known errors
        # if error found, append the line to the error log
        self.__read_each_lines(self.file)

        self.new_error_added = False

    #   Reads through all the lines of the files.
    #   Once an error is found, append the string to the self.errors dictionary

    def __read_each_lines(self, open_file):
        file_lines = self.file.readlines()
        print("total numbers of lines in log: " + str(len(file_lines)))
        for ln in file_lines:
            for e in self.errors:
                error_found = re.search(str(e), ln, re.M | re.I)
                if error_found:
                    date = ln[:6]
                    time = ln[7:15]
                    year = 2018        #ln[41:45]
                    principal_index = ln.find('AuditEvent')
                    principal_user = None

                    month_to_number = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                                       "Aug", "Sep", "Oct", "Nov", "Dec"]
                    month_number = month_to_number.index(date[:3])
                    day_number = date[4:6]
                    if month_to_number.index(date[:3]) + 1 < 10:
                        month_number = "0" + str(month_to_number.index(date[:3]) + 1)
                    if int(day_number) < 10:
                        day_number = "0" + str(day_number)
                    formatted_date =  month_number + "/" + day_number + "/" + str(year)

                    self.errors[e].append((formatted_date, time, ln))

    # close logReader and return the errorlog
    def close(self):
        if self.new_error_added:
            print("New error added during this session")
        self.file.close()
        return self.errors
