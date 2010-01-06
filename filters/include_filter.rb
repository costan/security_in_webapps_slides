module Codex::CodeFilter
  # Includes the contents of a file verbatim in the slide's HTML code.
  #
  #     :include code/html/tables.html
  class IncludeFilter < CodeFilter
    tag :include

    def format_code(desc, code)
      # "<notextile>#{code}</notextile>\n"
      code
    end
  end
end
