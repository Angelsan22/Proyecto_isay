from fpdf import FPDF
from datetime import datetime

class PDFGenerator(FPDF):
    def header(self):
        self.set_font("helvetica", "B", 24)
        self.set_text_color(255, 115, 36)
        self.cell(0, 10, "MACUIN", ln=True, align="L")
        self.set_font("helvetica", "I", 10)
        self.set_text_color(100, 100, 100)
        self.cell(0, 5, "Refaccionaria y Servicio Automotriz", ln=True, align="L")
        self.set_draw_color(255, 115, 36)
        self.set_line_width(1)
        self.line(10, 30, 200, 30)
        self.ln(10)

    def footer(self):
        self.set_y(-15)
        self.set_font("helvetica", "I", 8)
        self.set_text_color(128, 128, 128)
        self.cell(0, 10, f"Pagina {self.page_no()} / {{nb}}", align="C")
        self.cell(0, 10, f"Generado el: {datetime.now().strftime('%d/%m/%Y %H:%M')}", align="R")

    def create_table(self, title, headers, data, column_widths):
        self.add_page()
        self.set_font("helvetica", "B", 16)
        self.set_text_color(33, 37, 41)
        self.cell(0, 10, title.upper(), ln=True, align="C")
        self.ln(5)
        self.set_font("helvetica", "B", 10)
        self.set_fill_color(255, 115, 36)
        self.set_text_color(255, 255, 255)
        
        for i, header in enumerate(headers):
            self.cell(column_widths[i], 10, header, border=1, align="C", fill=True)
        self.ln()
        self.set_font("helvetica", "", 9)
        self.set_text_color(33, 37, 41)
        
        fill = False
        for row in data:
            self.set_fill_color(248, 249, 250)
            max_h = 8
            for i, item in enumerate(row):
                self.cell(column_widths[i], max_h, str(item), border=1, align="L" if i == 0 else "C", fill=fill)
            self.ln()
            fill = not fill

def generate_report(title, headers, data, col_widths):
    pdf = PDFGenerator()
    pdf.alias_nb_pages()
    pdf.set_auto_page_break(auto=True, margin=15)
    pdf.create_table(title, headers, data, col_widths)
    return pdf.output()
